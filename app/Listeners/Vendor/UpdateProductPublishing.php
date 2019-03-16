<?php

namespace App\Listeners\Vendor;

use App\Models\Product;
use App\Models\VendorCategory;

class UpdateProductPublishing
{
    /**
     * @var VendorCategory
     */
    private $vendorCategory;

    /**
     * UpdateProductPublishing constructor.
     * @param VendorCategory $vendorCategory
     */
    public function __construct(VendorCategory $vendorCategory)
    {
        $this->vendorCategory = $vendorCategory;
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     * @throws \Exception
     */
    public function handle($event)
    {
        // get product
        $product = $event->product;

        if ($this->isProductOnOwnStorage($product)) {
            $product->published = 1;
        } else {
            // calculate min vendor's offer price
            $minVendorsPrice = $product->vendorProducts()->min('price');

            if ($product->price1 && $minVendorsPrice) {
                $vendorCategories = $this->vendorCategory->newQuery()->whereHas('vendorProducts', function ($query) use ($product){
                    $query->where('products_id', $product->id);
                });

                //get min profit sum to publish product
                $minProfitSumToPublish = $vendorCategories->min('publish_product_min_profit_sum');
                //get min profit percents to publish product
                $minProfitPercentsToPublish = $vendorCategories->min('publish_product_min_profit_percent');

                // max profit sum
                $maxProductProfitSum = $product->price1 - $minVendorsPrice;
                // max profit percents
                $maxProductProfitPercents = $maxProductProfitSum / $minVendorsPrice * 100;

                $product->published = ($maxProductProfitSum > $minProfitSumToPublish) || ($maxProductProfitPercents > $minProfitPercentsToPublish);
            } else {
                $product->published = 1;
            }
        }

        $product->save();
    }

    /**
     * Is product on own storage ?
     *
     * @param Product $product
     * @return bool
     */
    private function isProductOnOwnStorage(Product $product): bool
    {
        return (bool)$product->stockStorages->count();
    }
}
