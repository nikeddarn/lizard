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
     * @param $user
     * @return float|null
     */
    public function getUserProductPrice(Product $product, $user = null)
    {
        $priceColumn = 'price' . $this->definePriceGroup($user);

        return $product->{$priceColumn};
    }

    /**
     * Define price group column.
     *
     * @param $user
     * @return string
     */
    private function definePriceGroup($user = null): string
    {

        return $user ? $user->price_group : 1;
    }
}
