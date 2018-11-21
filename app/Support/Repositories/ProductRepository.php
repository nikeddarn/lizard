<?php
/**
 * Product repository.
 */

namespace App\Support\Repositories;


use App\Models\Product;
use App\Models\VendorProduct;

class ProductRepository
{
    /**
     * Get product by related vendor product model with given own vendor product's is.
     *
     * @param int $vendorId
     * @param int $vendorProductId
     * @return Product|null
     */
    public function getProductByVendorId(int $vendorId, int $vendorProductId)
    {
        return Product::whereHas('vendorProducts', function ($query) use ($vendorId, $vendorProductId) {
            $query->where('vendor_product_id', $vendorProductId)
                ->whereHas('vendorCategory', function ($query) use ($vendorId) {
                    $query->where('vendors_id', $vendorId);
                });
        })
            ->with('vendorProducts')
            ->first();
    }

    /**
     * Get an existing product with fields equal to the inserted.
     *
     * @param array $productData
     * @return Product|null
     */
    public function getProductByModelData(array $productData)
    {
        $searchDoubleKeys = config('shop.search_double_by.product');

        return Product::where(function ($query) use ($searchDoubleKeys, $productData) {
            foreach ($searchDoubleKeys as $field) {
                $query->orWhere($field, $productData[$field]);
            }
        })
            ->first();
    }

//    /**
//     * Get vendor product by vendor product's id.
//     *
//     * @param int $vendorProductId
//     * @return VendorProduct|null
//     */
//    public function getVendorProduct(int $vendorProductId)
//    {
//        return VendorProduct::where('vendor_product_id', $vendorProductId)->first();
//    }
//
//    /**
//     * Does vendor product with given vendor product's id exist ?
//     *
//     * @param int $vendorProductId
//     * @return bool
//     */
//    public function vendorProductExists(int $vendorProductId)
//    {
//        return (bool)VendorProduct::where('vendor_product_id', $vendorProductId)->count();
//    }


}