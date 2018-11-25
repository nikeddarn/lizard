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
     * Get product by related vendor product model with given own vendor product's id.
     *
     * @param VendorProduct $vendorProduct
     * @return Product|Model|null
     */
    public function getProductByVendorProduct(VendorProduct $vendorProduct)
    {
        return $vendorProduct->product()->first();
    }

    /**
     * Get an existing product with fields equal to the inserted.
     *
     * @param array $productData
     * @return Product|Model|null
     */
    public function getProductByModelData(array $productData)
    {
        $searchDoubleKeys = config('shop.search_double_by.product');

        return Product::query()->where(function ($query) use ($searchDoubleKeys, $productData) {
            foreach ($searchDoubleKeys as $field) {
                $query->orWhere($field, $productData[$field]);
            }
        })
            ->first();
    }
}