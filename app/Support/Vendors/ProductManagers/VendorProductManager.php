<?php
/**
 * Vendor product manager.
 */

namespace App\Support\Vendors\ProductManagers;


use App\Contracts\Shop\AttributesInterface;
use App\Contracts\Vendor\VendorAdapterInterface;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\VendorProduct;
use App\Models\VendorStock;
use App\Support\Repositories\AttributeRepository;
use App\Support\Repositories\BrandRepository;
use App\Support\Repositories\ProductRepository;
use Carbon\Carbon;
use Exception;

abstract class VendorProductManager
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var BrandRepository
     */
    private $brandRepository;
    /**
     * @var AttributeRepository
     */
    private $attributeRepository;

    /**
     * VendorProductManager constructor.
     * @param ProductRepository $productRepository
     * @param BrandRepository $brandRepository
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(ProductRepository $productRepository, BrandRepository $brandRepository, AttributeRepository $attributeRepository)
    {
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Retrieve existing Product model (with VendorProduct model) or create it anew.
     * Attach Product to local category.
     *
     * @param VendorProduct $vendorProduct
     * @param int $localCategoryId
     * @param array $productModelData
     * @return Product
     */
    protected function insertProductData(VendorProduct $vendorProduct, int $localCategoryId, array $productModelData): Product
    {
        // try to retrieve existing vendor product by vendor product
        $product = $this->productRepository->getProductByVendorProduct($vendorProduct);

        // try to retrieve existing vendor product by product's data
        if (!$product) {
            $product = $this->productRepository->getProductByModelData($productModelData);
        }

        // create product
        if (!$product) {
            $product = Product::query()->create($productModelData);
        }

        // attach product to category
        $product->categories()->syncWithoutDetaching([$localCategoryId]);

        return $product;
    }

    /**
     * @param VendorProduct $vendorProduct
     * @param int $productId
     * @param int $vendorCategoryId
     * @param array $vendorProductModelData
     */
    public function insertVendorProductData(VendorProduct $vendorProduct, int $productId, int $vendorCategoryId, array $vendorProductModelData)
    {
        // add product id to vendor product data
        $vendorProductModelData['products_id'] = $productId;

        // update vendor product
        $vendorProduct->update($vendorProductModelData);

        // attach vendor product to vendor category
        $vendorProduct->vendorCategories()->syncWithoutDetaching([$vendorCategoryId]);
    }

    /**
     * Insert product badges.
     *
     * @param Product $product
     * @param array $badgesIds
     * @return bool
     */
    protected function insertProductBadges(Product $product, array $badgesIds): bool
    {
        foreach ($badgesIds as $badgeId) {

            $badgeTTL = config('shop.badges.ttl.' . $badgeId);
            $badgeExpired = $badgeTTL ? Carbon::now()->addDays($badgeTTL) : null;

            $product->badges()->syncWithoutDetaching([
                $badgeId => [
                    'expired' => $badgeExpired,
                ],
            ]);
        }

        return true;
    }

    /**
     * Insert vendor stocks product data.
     *
     * @param $vendorAdapter
     * @param int $vendorProductId
     * @param array $productStocksData
     */
    protected function insertVendorStocksProductData(VendorAdapterInterface $vendorAdapter, int $vendorProductId, array $productStocksData)
    {
        foreach ($productStocksData as $vendorStockId => $productStockData) {

            // retrieve vendor stock
            $vendorStock = VendorStock::query()->where([
                ['vendors_id', '=', $vendorAdapter->getVendorId()],
                ['vendor_stock_id', '=', $vendorStockId],
            ])->first();

            // create new vendor stock
            if (!$vendorStock) {
                try {
                    $stockData = $vendorAdapter->getStockDataByVendorStockId($vendorStockId);
                    $vendorStock = VendorStock::query()->create($stockData);
                } catch (Exception $exception) {
                    continue;
                }
            }

            if ($vendorStock) {
                $vendorStock->vendorProducts()->syncWithoutDetaching([
                    $vendorProductId => $productStockData,
                ]);
            }
        }
    }

    /**
     * Insert brand attribute.
     *
     * @param VendorAdapterInterface $vendorAdapter
     * @param int $productId
     * @param int $vendorBrandId
     * @return bool
     */
    protected function insertProductBrand(VendorAdapterInterface $vendorAdapter, int $productId, int $vendorBrandId): bool
    {
        // retrieve brand attribute value
        $brandAttributeValue = $this->brandRepository->getBrandValueByVendorId($vendorAdapter->getVendorId(), $vendorBrandId);

        // create new brand attribute value
        if (!$brandAttributeValue) {
            try {
                $vendorBrandValueData = $vendorAdapter->getBrandDataByVendorBrandId($vendorBrandId);
            } catch (Exception $exception) {
                return false;
            }
            // retrieve brand value by data (prevent insert double)
            $brandAttributeValue = $this->brandRepository->getBrandValueByModelData($vendorBrandValueData);

            if (!$brandAttributeValue) {
                // get brand attribute
                $brandAttribute = Attribute::query()->where('defined_attribute_id', AttributesInterface::BRAND)->first();
                // create new brand value
                $brandAttributeValue = $brandAttribute->attributeValues()->create($vendorBrandValueData);
            }
        }

        $brandAttributeValue->products()->syncWithoutDetaching([
            $productId => [
                'attributes_id' => $brandAttributeValue->attributes_id,
            ],
        ]);

        return true;
    }

    /**
     * Insert product attributes and values.
     *
     * @param Product $product
     * @param int $vendorId
     * @param array $attributesData
     */
    protected function insertProductAttributes(Product $product, int $vendorId, array $attributesData)
    {
        foreach ($attributesData as $attributeData) {
            // insert vendor attribute and attach it to vendor
            $attribute = $this->insertVendorAttribute($vendorId, $attributeData['attribute']);
            // insert vendor attribute value and attach it to vendor
            $attributeValue = $this->insertVendorAttributeValue($attribute, $vendorId, $attributeData['attribute_value']);

            // attach attribute value to product
            $product->attributeValues()->syncWithoutDetaching([
                $attributeValue->id => [
                    'attributes_id' => $attribute->id,
                ]
            ]);
        }
    }

    /**
     * Insert product images.
     *
     * @param Product $product
     * @param array $productImages
     */
    protected function insertProductImages(Product $product, array $productImages)
    {

    }

    /**
     * Insert vendor attribute.
     *
     * @param int $vendorId
     * @param array $attributeData
     * @return Attribute
     */
    private function insertVendorAttribute(int $vendorId, array $attributeData): Attribute
    {
        // retrieve attribute by vendor id
        $attribute = $this->attributeRepository->getAttributeByVendorId($vendorId, $attributeData['vendor_attribute_id']);

        if (!$attribute) {
            // retrieve attribute by data
            $attribute = $this->attributeRepository->getAttributeByModelData($attributeData['data']);

            // create attribute
            if (!$attribute) {
                $attribute = Attribute::query()->create($attributeData['data']);
            }

            // attach attribute to vendor
            $attribute->vendors()->attach($vendorId, [
                'vendor_attribute_id' => $attributeData['vendor_attribute_id'],
            ]);
        }

        return $attribute;
    }

    /**
     * Insert vendor attribute's value.
     *
     * @param Attribute $attribute
     * @param int $vendorId
     * @param array $attributeValueData
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function insertVendorAttributeValue(Attribute $attribute, int $vendorId, array $attributeValueData)
    {
        // retrieve attribute value by vendor id
        $attributeValue = $this->attributeRepository->getAttributeValueByVendorId($vendorId, $attributeValueData['vendor_attribute_value_id']);

        if (!$attributeValue) {
            // retrieve attribute value by data
            $attributeValue = $this->attributeRepository->getAttributeValueByModelData($attributeValueData['data']);

            // create attribute value
            if (!$attributeValue) {
                $attributeValue = $attribute->attributeValues()->create($attributeValueData['data']);
            }

            // attach attribute value to vendor
            $attributeValue->vendors()->attach($vendorId, [
                'vendor_attribute_value_id' => $attributeValueData['vendor_attribute_value_id'],
            ]);
        }


        return $attributeValue;
    }
}