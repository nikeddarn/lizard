<?php

namespace App\Listeners\Vendor;

use App\Contracts\Shop\StorageDepartmentsInterface;
use App\Models\Product;
use App\Support\Settings\SettingsRepository;

class UpdateProductPublishing
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * Create the event listener.
     *
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
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
            $minVendorPrices = $product->vendorProducts()->min('price');

            if ($minVendorPrices) {
                // max profit
                $maxProductProfit = $product->price1 - $minVendorPrices;

                //get min profit to publish product
                $minProfitToPublish = $this->settingsRepository->getProperty('vendor.min_profit_sum_to_offer_product');

                $product->published = $maxProductProfit > $minProfitToPublish;
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
    private function isProductOnOwnStorage(Product $product)
    {
        return (bool)$product->storageProducts()
            ->where([
                ['storage_departments_id', '=', StorageDepartmentsInterface::STOCK],
                ['stock_quantity', '>', 0],
            ])
            ->count();
    }
}
