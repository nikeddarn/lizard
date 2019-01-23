<?php
/**
 * Product repository.
 */

namespace App\Support\Repositories;


use App\Models\Product;
use App\Models\VendorProduct;
use Illuminate\Database\Eloquent\Model;

class ProductRepository
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var VendorProduct
     */
    private $vendorProduct;

    /**
     * ProductRepository constructor.
     * @param Product $product
     * @param VendorProduct $vendorProduct
     */
    public function __construct(Product $product, VendorProduct $vendorProduct)
    {
        $this->product = $product;
        $this->vendorProduct = $vendorProduct;
    }

    /**
     * Retrieve existing Product model by fields or create it anew.
     *
     * @param array $productModelData
     * @return Product
     */
    public function findOrMakeProduct(array $productModelData): Product
    {
        // try to retrieve existing vendor product by product's data
        $product = $this->getProductByModelData($productModelData);

        // create product
        if (!$product) {
            $product = $this->createProduct($productModelData);
        }

        return $product;
    }

    /**
     * Get product by related vendor product model with given own vendor product's id.
     *
     * @param int $vendorId
     * @param int $vendorProductId
     * @return Product|Model|null
     */
    public function getProductByVendorProductId(int $vendorId, int $vendorProductId)
    {
        return $this->product->newQuery()
            ->whereHas('vendorProduct', function ($query) use ($vendorId, $vendorProductId) {
                $query->where([
                    ['vendors_id', '=', $vendorId],
                    ['vendor_product_id', '=', $vendorProductId],
                ]);
            })
            ->with(['vendorProduct' => function($query) use ($vendorId, $vendorProductId) {
                $query->where([
                    ['vendors_id', '=', $vendorId],
                    ['vendor_product_id', '=', $vendorProductId],
                ]);
            }])
            ->first();
    }

    /**
     * Get an existing product with fields equal to the inserted.
     *
     * @param array $productData
     * @return Product|Model|null
     */
    public function getProductByModelData(array $productData)
    {
        /**
         * ToDo Enhance parser. Store product in table to merge attributes by manager.
         */

        $searchDoubleKeys = config('vendor.search_double_by.product');

        return $this->product->newQuery()
            ->where(function ($query) use ($searchDoubleKeys, $productData) {
                foreach ($searchDoubleKeys as $field) {
                    $query->orWhere($field, $productData[$field]);
                }
            })
            ->first();
    }

    /**
     * Create new product.
     *
     * @param array $productData
     * @return Product|Model
     */
    public function createProduct(array $productData)
    {
        return $this->product->newQuery()->create($productData);
    }
}
