<?php
/**
 * Define vendor product price.
 */

namespace App\Support\ProductPrices;


use App\Models\Product;
use App\Models\VendorProduct;
use App\Support\Settings\SettingsRepository;
use Exception;
use Illuminate\Support\Collection;

class VendorProductPrice
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * VendorProductPrice constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Define product column prices.
     *
     * @param Product $product
     * @return array
     * @throws Exception
     */
    public function getProductPrices(Product $product): array
    {
        // get vendor products
        $vendorProducts = $product->vendorProducts()->get();

        if (!$vendorProducts->count()) {
            throw new Exception('Missing any vendor product');
        }

        // get processing products
        $processingVendorProducts = $this->getProcessingVendorProducts($vendorProducts);

        // base retail price
        $baseProductRetailPrice = $this->getBaseProductRetailPrice($processingVendorProducts);

        // base product profit
        $baseProductIncomingPrice = $this->getBaseProductIncomingPrice($processingVendorProducts);

        // create product prices
        if ($baseProductIncomingPrice && $baseProductRetailPrice) {

            // calculate profit
            $profitSum = $baseProductRetailPrice - $baseProductIncomingPrice;
            $profitPercentages = $profitSum / $baseProductIncomingPrice * 100;

            // discount settings
            $discountSettings = $this->settingsRepository->getProperty('vendor.price_discount');

            if ($profitSum > $discountSettings['min_profit_sum_to_price_discount'] || $profitPercentages > $discountSettings['min_profit_percents_to_price_discount']) {

                $price1 = $this->getVendorProductColumnPrice($baseProductIncomingPrice, $baseProductRetailPrice, $discountSettings['column_discounts']['price1']);

                $price2 = $this->getVendorProductColumnPrice($baseProductIncomingPrice, $baseProductRetailPrice, $discountSettings['column_discounts']['price2']);

                $price3 = $this->getVendorProductColumnPrice($baseProductIncomingPrice, $baseProductRetailPrice, $discountSettings['column_discounts']['price3']);

            } else {
                $price1 = $price2 = $price3 = $baseProductRetailPrice;
            }
        } else {
            $price1 = $price2 = $price3 = null;
        }

        return compact('price1', 'price2', 'price3');
    }

    /**
     * Filter single vendor product or only vendor products that have product on any storage.
     *
     * @param Collection $vendorProducts
     * @return Collection
     */
    private function getProcessingVendorProducts(Collection $vendorProducts): Collection
    {
        $vendorPriceConditions = $this->settingsRepository->getProperty('vendor.product_price_conditions');

        if ($vendorProducts->count() === 1 || !$vendorPriceConditions['use_vendor_available_product_to_calculate_price']) {
            // use single vendor product
            return $vendorProducts;
        } else {
            // get storage available vendor products
            $storageAvailableVendorProducts = $vendorProducts->filter(function (VendorProduct $vendorProduct) {
                return (bool)$vendorProduct->availability;
            });

            if ($storageAvailableVendorProducts->count()) {
                // use storage available vendor products
                return $storageAvailableVendorProducts;
            } else {
                // use all vendor products
                return $vendorProducts;
            }
        }
    }

    /**
     * Calculate base product retail price.
     *
     * @param Collection $processingVendorProducts
     * @return float|null
     */
    private function getBaseProductRetailPrice(Collection $processingVendorProducts)
    {
        // possible table columns to retrieve retail price
        $retailPriceColumns = config('vendor.price.use_vendor_retail_price_column');

        // method name to aggregate vendor product prices from multi vendor
        $usingAggregatePriceMethod = config('vendor.price.multi_vendor_aggregate_product_price_method');

        foreach ($retailPriceColumns as $usingPriceColumn) {
            // aggregate 'recommendable_price' or 'retail_price' of vendor products with 'min', 'avg' or 'max' methods
            $baseRetailPrice = $processingVendorProducts->$usingAggregatePriceMethod($usingPriceColumn);

            if ($baseRetailPrice > 0) {
                return $baseRetailPrice;
            }
        }

        return null;
    }

    /**
     * Calculate base product incoming price.
     *
     * @param Collection $processingVendorProducts
     * @return float|null
     */
    private function getBaseProductIncomingPrice(Collection $processingVendorProducts)
    {
        // vendor product column name for retrieve incoming price
        $usingPriceColumn = 'price';

        // method name to aggregate vendor product incoming prices from multi vendor
        $usingAggregatePriceMethod = config('vendor.price.multi_vendor_aggregate_product_price_method');

        // aggregate incoming price of vendor products with 'min', 'avg' or 'max' methods
        $baseIncomingPrice = $processingVendorProducts->$usingAggregatePriceMethod($usingPriceColumn);

        return $baseIncomingPrice;
    }

    /**
     * Get price for vendor product column by column name.
     *
     * @param float $baseProductIncomingPrice
     * @param float $baseProductRetailPrice
     * @param int $productPriceColumnDiscount
     * @return float
     */
    private function getVendorProductColumnPrice(float $baseProductIncomingPrice, float $baseProductRetailPrice, int $productPriceColumnDiscount): float
    {
        // calculate profit
        $profitSum = $baseProductRetailPrice - $baseProductIncomingPrice;

        if ($productPriceColumnDiscount) {
            return $baseProductRetailPrice - $profitSum * min($productPriceColumnDiscount / 100, 1);
        } else {
            return $baseProductRetailPrice;
        }
    }
}
