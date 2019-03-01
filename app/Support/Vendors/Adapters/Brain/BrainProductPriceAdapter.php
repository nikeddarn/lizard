<?php
/**
 * Brain product price adapter.
 */

namespace App\Support\Vendors\Adapters\Brain;

use stdClass;

class BrainProductPriceAdapter
{
    /**
     * Prepare vendor product prices.
     *
     * @param stdClass $vendorProductData
     * @param float $vendorUsdCourse
     * @return array
     */
    public function prepareVendorProductPrices(stdClass $vendorProductData, float $vendorUsdCourse): array
    {
        // calculate USD prices
        $incomingPrice = $vendorProductData->price > 0 ? round($vendorProductData->price, 2) : null;

        $recommendablePrice = $vendorProductData->recommendable_price > 0 ? round($vendorProductData->recommendable_price / $vendorUsdCourse, 2) : null;

        $retailPrice = $vendorProductData->retail_price_uah > 0 ? round($vendorProductData->retail_price_uah / $vendorUsdCourse, 2) : null;

        return [
            'price' => $incomingPrice,
            'recommendable_price' => $recommendablePrice,
            'retail_price' => $retailPrice,
        ];
    }
}
