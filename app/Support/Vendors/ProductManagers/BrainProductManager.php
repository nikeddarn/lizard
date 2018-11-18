<?php
/**
 * Brain product manager.
 */

namespace App\Support\Vendors\ProductManagers;


use App\Contracts\Vendor\VendorInterface;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\VendorProduct;
use App\Support\Vendors\Adapters\BrainVendorAdapter;

class BrainProductManager
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
     * @param array $vendorProductsIds
     * @throws \Exception
     */
    public function insertVendorProducts(int $vendorCategoryId, int $localCategoryId, array $vendorProductsIds)
    {
        $vendorUsdCourse = $this->vendorAdapter->getCashUsdCourse();

        foreach ($vendorProductsIds as $vendorProductId) {

            $productData = $this->vendorAdapter->getProductData($vendorProductId, $vendorUsdCourse);

            // create product
            $product = $this->getProduct($productData['product']);
            // attach local category
            $product->categories()->attach($localCategoryId);
            //attach product brand attribute
            $this->insertProductBrand($product, $productData['vendor_brand_id']);
            //insert badges


            // create vendor product
            $vendorProduct = $this->getVendorProduct($productData['vendor_product'], $vendorCategoryId, $product->id);
            // insert vendor stocks product data
            $this->insertVendorStocksProductData($vendorProduct, $productData['product_stocks_data']);
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
     * @param array $productData
     * @return Product
     */
    private function getProduct(array $productData): Product
    {
        $product = Product::where('name_ru', $productData['name_ru'])->orWhere('name_ua', $productData['name_ua'])->orWhere('url', $productData['url'])->first();

        if (!$product) {
            $product = Product::create($productData);
        }

        return $product;
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
     */
    private function insertProductBrand(Product $product, int $vendorBrandId)
    {
        $productBrandAttributeValue = AttributeValue::whereHas('vendorAttributeValues', function ($query) use ($vendorBrandId) {
            $query->where([
                ['vendors_id', '=', VendorInterface::BRAIN],
                ['vendor_attribute_value_id', '=', $vendorBrandId],
            ]);
        })
            ->with('attribute')
            ->first();

        $product->attributeValues()->attach($productBrandAttributeValue->id, [
            'attributes_id' => $productBrandAttributeValue->attribute->id,
        ]);
    }

    /**
     * Insert vendor stocks product data.
     *
     * @param VendorProduct $vendorProduct
     * @param array $productStocksData
     */
    private function insertVendorStocksProductData(VendorProduct $vendorProduct, array $productStocksData)
    {
        foreach ($productStocksData as $stockId => $stocksData){
            $vendorProduct->vendorStocks()->attach($stockId, $stocksData);
        }
    }
}