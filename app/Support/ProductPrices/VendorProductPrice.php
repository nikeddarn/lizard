<?php
/**
 * Define vendor product price.
 */

namespace App\Support\ProductPrices;


use App\Models\Product;
use App\Models\VendorProduct;
use Exception;
use Illuminate\Support\Collection;

class VendorProductPrice
{
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

        // vendors ids
        $vendorsIds = $processingVendorProducts->pluck('vendors_id')->toArray();

        // base retail price
        $baseProductRetailPrice = $this->getBaseProductRetailPrice($processingVendorProducts);

        // base product profit
        $baseProductIncomingPrice = $this->getBaseProductIncomingPrice($processingVendorProducts);

        // create product prices
        if ($baseProductIncomingPrice && $baseProductRetailPrice) {
            $price1 = $this->getVendorProductColumnPrice('price1', $baseProductIncomingPrice, $baseProductRetailPrice, $vendorsIds);
            $price2 = $this->getVendorProductColumnPrice('price2', $baseProductIncomingPrice, $baseProductRetailPrice, $vendorsIds);
            $price3 = $this->getVendorProductColumnPrice('price3', $baseProductIncomingPrice, $baseProductRetailPrice, $vendorsIds);
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
        if ($vendorProducts->count() === 1 || !config('shop.price.vendor_available_product_only')) {
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
        $retailPriceColumns = config('shop.price.use_vendor_retail_price_column');

        // method name to aggregate vendor product prices from multi vendor
        $usingAggregatePriceMethod = config('shop.price.multi_vendor_aggregate_product_price_method');

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
        $usingAggregatePriceMethod = config('shop.price.multi_vendor_aggregate_product_price_method');

        // aggregate incoming price of vendor products with 'min', 'avg' or 'max' methods
        $baseIncomingPrice = $processingVendorProducts->$usingAggregatePriceMethod($usingPriceColumn);

        return $baseIncomingPrice;
    }

    /**
     * Get price for vendor product column by column name.
     *
     * @param string $productPriceColumnName
     * @param float $baseProductIncomingPrice
     * @param float $baseProductRetailPrice
     * @param array $vendorsIds
     * @return float
     */
    private function getVendorProductColumnPrice(string $productPriceColumnName, float $baseProductIncomingPrice, float $baseProductRetailPrice, array $vendorsIds): float
    {
        // method name to aggregate vendor product incoming prices from multi vendor
        $usingAggregateDiscountMethod = config('shop.price.multi_vendor_aggregate_column_price_discount_method');

        // calculate profit
        $profit = $baseProductRetailPrice - $baseProductIncomingPrice;

        // min profit to discount
        $minProfitToDiscount = $baseProductIncomingPrice * config('shop.price.min_profit_to_price_discount') / 100;

        if ($profit > $minProfitToDiscount) {
            $columnDiscount = collect(config('shop.price.vendor_column_price_discount'))->only($vendorsIds)->$usingAggregateDiscountMethod($productPriceColumnName);

            $discountedColumnPrice = $columnDiscount ? $baseProductRetailPrice - $profit * min($columnDiscount, 1) : $baseProductRetailPrice;

            return $discountedColumnPrice;
        } else {
            return $baseProductRetailPrice;
        }
    }
}
