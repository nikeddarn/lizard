<?php
/**
 * Brain product manager.
 */

namespace App\Support\Vendors\ProductManagers;


use App\Contracts\Shop\AttributesInterface;
use App\Contracts\Shop\ProductBadgesInterface;
use App\Contracts\Vendor\VendorInterface;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\VendorProduct;
use App\Support\Vendors\Adapters\BrainVendorAdapter;
use Carbon\Carbon;
use Exception;

class BrainProductManager extends VendorProductManager
{
    /**
     * @var BrainVendorAdapter
     */
    private $vendorAdapter;

    /**
     * BrainProductManager constructor.
     * @param BrainVendorAdapter $vendorAdapter
     */
    public function __construct(BrainVendorAdapter $vendorAdapter)
    {
        $this->vendorAdapter = $vendorAdapter;
    }

    /**
     * Insert vendor products data in db.
     *
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @param int $vendorProductId
     * @throws Exception
     */
    public function insertVendorProduct(int $vendorCategoryId, int $localCategoryId, int $vendorProductId)
    {
        // get product data
        $productData = $this->vendorAdapter->getProductData($vendorProductId);

        // Retrieve existing or create product and vendor product models
        $product = $this->getOrCreateProduct(VendorInterface::BRAIN, $vendorCategoryId, $localCategoryId, $vendorProductId, $productData['product'], $productData['vendor_product']);

        // attach local category
        $product->categories()->attach($localCategoryId);
        //attach product brand attribute
        $this->insertProductBrand($product, $productData['vendor_brand_id']);
        //insert badges
        $this->insertProductBadges($product, $productData['product_stocks_data']);


        // insert vendor stocks product data
        $this->insertVendorStocksProductData($vendorProduct, $productData['product_stocks_data']);

        // get attributes data
        $attributesData = $this->vendorAdapter->getProductAttributesData($vendorProductId);

        // insert vendor attributes and its values
        foreach ($attributesData as $attributeData) {
            $attribute = $this->insertVendorAttribute($attributeData['attribute']);
            $attributeValue = $this->insertVendorAttributeValue($attribute, $attributeData['attribute_value']);
            $product->attributeValues()->syncWithoutDetaching([
                $attributeValue->id => [
                    'attributes_id' => $attribute->id,
                ]
            ]);
        }
    }

    /**
     * Delete vendor products data in db.
     *
     * @param array $vendorProductsIds
     */
    public function deleteVendorProducts(array $vendorProductsIds)
    {

    }

    /**
     * Get or create product.
     *
     * @param array $vendorProductData
     * @param int $vendorCategoryId
     * @param int $productId
     * @return VendorProduct
     */
    private function getVendorProduct(array $vendorProductData, int $vendorCategoryId, int $productId): VendorProduct
    {
        $vendorProductData['vendor_categories_id'] = $vendorCategoryId;
        $vendorProductData['products_id'] = $productId;

        return VendorProduct::create($vendorProductData);
    }

    /**
     * Insert brand attribute.
     *
     * @param Product $product
     * @param int $vendorBrandId
     * @throws Exception
     */
    private function insertProductBrand(Product $product, int $vendorBrandId)
    {
        // retrieve brand attribute value
        $brandAttributeValue = AttributeValue::whereHas('vendorAttributeValues', function ($query) use ($vendorBrandId) {
            $query->where([
                ['vendors_id', '=', VendorInterface::BRAIN],
                ['vendor_attribute_value_id', '=', $vendorBrandId],
            ]);
        })
            ->with('attribute')
            ->first();

        if ($brandAttributeValue) {
            // attach brand value to product
            $product->attributeValues()->attach($brandAttributeValue->id, [
                'attributes_id' => $brandAttributeValue->attribute->id,
            ]);
        } else {
            // get brand attribute
            $brandAttribute = Attribute::where('defined_attribute_id', AttributesInterface::BRAND)->first();

            // get brand value data
            $vendorBrandValueData = $this->vendorAdapter->getBrandDataByVendorBrandId($vendorBrandId);

            // retrieve attribute value
            $vendorBrandValue = $brandAttribute->attributeValues()->where('value_ru', $vendorBrandValueData['value_ru'])->orWhere('url', $vendorBrandValueData['url'])->first();

            if (!$vendorBrandValue) {
                // create new brand value
                $vendorBrandValue = $brandAttribute->attributeValues()->create($vendorBrandValueData);
            }

            // attach brand value to vendor
            $vendorBrandValue->vendors()->attach(VendorInterface::BRAIN, [
                'vendor_attribute_value_id' => $vendorBrandValue->id,
            ]);

            // attach brand value to product
            $product->attributeValues()->attach($vendorBrandValue->id, [
                'attributes_id' => $brandAttribute->id,
            ]);
        }
    }

    /**
     * Insert vendor stocks product data.
     *
     * @param VendorProduct $vendorProduct
     * @param array $productStocksData
     */
    private function insertVendorStocksProductData(VendorProduct $vendorProduct, array $productStocksData)
    {
        foreach ($productStocksData as $stockId => $stocksData) {
            $vendorProduct->vendorStocks()->attach($stockId, $stocksData);
        }
    }

    /**
     * Insert badges.
     *
     * @param Product $product
     * @param array $productStocksData
     */
    private function insertProductBadges(Product $product, array $productStocksData)
    {
        // add 'new' badge
        $newProductBadgeExpired = Carbon::now()->addDays(config('shop.badges.ttl.' . ProductBadgesInterface::NEW));
        $product->badges()->attach(ProductBadgesInterface::NEW, ['expired' => $newProductBadgeExpired]);

        // add 'ending' badge
        $stocksAvailability = array_column($productStocksData, 'available');
        if (!empty($stocksAvailability) && max($stocksAvailability) === 1) {
            $product->badges()->attach(ProductBadgesInterface::ENDING);
        }
    }

    /**
     * Insert vendor attribute.
     *
     * @param array $attributeData
     * @return Attribute
     */
    private function insertVendorAttribute(array $attributeData): Attribute
    {
        // retrieve attribute by vendor id
        $attribute = Attribute::whereHas('vendorAttributes', function ($query) use ($attributeData) {
            $query->where('vendor_attribute_id', $attributeData['vendor_attribute_id']);
        })->first();

        if (!$attribute) {
            // retrieve attribute by data
            $attribute = Attribute::where('name_ru', $attributeData['data']['name_ru'])->orWhere('name_ua', $attributeData['data']['name_ua'])->first();

            // create attribute
            if (!$attribute) {
                $attribute = Attribute::create($attributeData['data']);
            }

            // attach attribute to vendor
            $attribute->vendors()->attach(VendorInterface::BRAIN, [
                'vendor_attribute_id' => $attributeData['vendor_attribute_id'],
            ]);
        }

        return $attribute;
    }

    /**
     * Insert vendor attribute's value.
     *
     * @param Attribute $attribute
     * @param array $attributeValueData
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function insertVendorAttributeValue(Attribute $attribute, array $attributeValueData)
    {
        // retrieve attribute by vendor id
        $attributeValue = AttributeValue::whereHas('vendorAttributeValues', function ($query) use ($attributeValueData) {
            $query->where('vendor_attribute_value_id', $attributeValueData['vendor_attribute_value_id']);
        })->first();

        if (!$attributeValue) {
            // retrieve attribute by data
            $attributeValue = AttributeValue::where('value_ru', $attributeValueData['data']['value_ru'])->orWhere('value_ua', $attributeValueData['data']['value_ua'])->orWhere('url', $attributeValueData['data']['url'])->first();

            // create attribute
            if (!$attributeValue) {
                $attributeValue = $attribute->attributeValues()->create($attributeValueData['data']);
            }

            // attach attribute to vendor
            $attributeValue->vendors()->attach(VendorInterface::BRAIN, [
                'vendor_attribute_value_id' => $attributeValueData['vendor_attribute_value_id'],
            ]);
        }

        return $attributeValue;
    }
}