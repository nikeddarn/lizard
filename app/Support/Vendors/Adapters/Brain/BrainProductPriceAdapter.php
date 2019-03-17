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
        $recommendablePrice = $this->calculateRecommendablePrice($vendorProductData, $vendorUsdCourse);
        $retailPrice = $this->calculateRetailPrice($vendorProductData, $vendorUsdCourse);

        return [
            'price' => $incomingPrice,
            'recommendable_price' => $recommendablePrice,
            'retail_price' => $retailPrice,
        ];
    }

    /**
     * Calculate recommendable price.
     *
     * @param $vendorProductData
     * @param float|null $vendorUsdCourse
     * @return float|null
     */
    private function calculateRecommendablePrice($vendorProductData, float $vendorUsdCourse = null)
    {
        if (!($vendorUsdCourse && $vendorProductData->recommendable_price)) {
            return null;
        }

        return round($vendorProductData->recommendable_price / $vendorUsdCourse, 2);
    }

    /**
     * Calculate retail price.
     *
     * @param $vendorProductData
     * @param float|null $vendorUsdCourse
     * @return float|null
     */
    private function calculateRetailPrice($vendorProductData, float $vendorUsdCourse = null)
    {
        if (!($vendorUsdCourse && $vendorProductData->retail_price_uah)) {
            return null;
        }

        return round($vendorProductData->retail_price_uah / $vendorUsdCourse, 2);
    }
}
