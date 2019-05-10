<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 19.10.18
 * Time: 22:32
 */

namespace App\Support\ProductAvailability;


use App\Models\Product;
use Carbon\Carbon;

class ProductAvailability
{
    /**
     * Is product available or expecting.
     *
     * @param Product $product
     * @return bool
     */
    public function isProductAvailableOrExpecting(Product $product):bool
    {
        return (bool)$product->availableOrExpectingStorageProducts->count() || (bool)$product->availableOrExpectingVendorProducts->count();
    }

    /**
     * Is product available on any local or vendor storages.
     *
     * @param Product $product
     * @return bool
     */
    public function isProductAvailable(Product $product): bool
    {
        return (bool)$product->availableStorageProducts->count() || (bool)$product->availableVendorProducts->count();
    }

    /**
     * Get min expected product time (or null)
     *
     * @param Product $product
     * @return Carbon|null
     */
    public function getProductExpectedTime(Product $product)
    {
        $minExpectedTimes = array_filter([
            $product->expectingStorageProducts->min('available_time'),
            $product->expectingVendorProducts->min('available_time'),
        ]);

        if (empty($minExpectedTimes)) {
            return null;
        } else {
            return Carbon::createFromFormat('Y-m-d H:i:s', min($minExpectedTimes));
        }
    }

    /**
     * Is product ending ?
     *
     * @param Product $product
     * @return bool
     */
    public function isProductEnding(Product $product): bool
    {
        return $product->vendorProducts()->max('available') === 1;
    }
}
