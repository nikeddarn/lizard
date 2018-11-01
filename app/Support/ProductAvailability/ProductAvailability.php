<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 19.10.18
 * Time: 22:32
 */

namespace App\Support\ProductAvailability;


use App\Contracts\Shop\StorageDepartmentsInterface;
use App\Models\Product;
use Carbon\Carbon;

class ProductAvailability
{
    public function getHavingProductStorages(Product $product)
    {
        return $product->stockStorages()->wherePivot('available_quantity', '>', 0)->with('city')->get();
    }

    /**
     * Get get nearest time of planned delivering product to storages.
     *
     * @param Product $product
     * @return null|Carbon
     */
    public function getProductAvailableTime(Product $product)
    {
        $storagesAvailableTime = $product->storageProducts()->groupBy('products_id')->min('available_time');

        if ($storagesAvailableTime){
            return Carbon::createFromFormat('Y-m-d H:i:s', $storagesAvailableTime);
        }

        return null;
    }
}