<?php
/**
 * Define product price.
 */

namespace App\Support\ProductPrices;


use App\Models\Product;

class ProductPrice
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
     * Get price1 for vendor product.
     *
     * @param int $vendorId
     * @param float $incomingPrice
     * @param float $recommendedPrice
     * @return float
     */
    public function getVendorProductPrice1(int $vendorId, float $incomingPrice, float $recommendedPrice):float
    {
        $profit = $recommendedPrice - $incomingPrice;

        return $recommendedPrice - $profit * min(config('shop.vendor_price_discount.price1'), 1);
    }

    /**
     * Get price2 for vendor product.
     *
     * @param int $vendorId
     * @param float $incomingPrice
     * @param float $recommendedPrice
     * @return float
     */
    public function getVendorProductPrice2(int $vendorId, float $incomingPrice, float $recommendedPrice):float
    {
        $profit = $recommendedPrice - $incomingPrice;

        return $recommendedPrice - $profit * min(config('shop.vendor_price_discount.price2'), 1);
    }

    /**
     * Get price3 for vendor product.
     *
     * @param int $vendorId
     * @param float $incomingPrice
     * @param float $recommendedPrice
     * @return float
     */
    public function getVendorProductPrice3(int $vendorId, float $incomingPrice, float $recommendedPrice):float
    {
        $profit = $recommendedPrice - $incomingPrice;

        return $recommendedPrice - $profit * min(config('shop.vendor_price_discount.price3'), 1);
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