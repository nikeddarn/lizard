<?php
/**
 * Define product price.
 */

namespace App\Support\ProductPrices;


use App\Models\Product;

class UserProductPrice
{
    /**
     * Define product price.
     *
     * @param Product $product
     * @return float|null
     */
    public function getUsersProductPrice(Product $product)
    {
        $priceColumn = $this->definePriceColumn();

        return $product->{$priceColumn};
    }

    /**
     * Define price group column.
     *
     * @return string
     */
    private function definePriceColumn():string
    {
        if (auth('web')->check()){
            return 'price' . auth('web')->user()->price_group;
        }else{
            return 'price1';
        }
    }
}
