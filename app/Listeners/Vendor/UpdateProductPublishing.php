<?php

namespace App\Listeners\Vendor;

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

        // calculate min vendor's offer price
        $minVendorPrices = $product->vendorProducts()->min('price');

        // max profit
        $maxProductProfit = $product->price1 - $minVendorPrices;

        //get min profit to publish product
        $minProfitToPublish = $this->settingsRepository->getProperty('vendor.min_profit_sum_to_offer_product');


        $product->published = $maxProductProfit > $minProfitToPublish;

        $product->save();
    }
}
