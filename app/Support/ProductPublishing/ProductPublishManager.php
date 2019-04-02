<?php
/**
 * Update product publishing.
 */

namespace App\Support\ProductPublishing;


use App\Models\Product;
use App\Models\VendorCategory;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\Settings\SettingsRepository;
use Illuminate\Support\Collection;

class ProductPublishManager
{
    /**
     * @var ProductAvailability
     */
    private $productAvailability;
    /**
     * @var VendorCategory
     */
    private $vendorCategory;
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * ProductPublishManager constructor.
     * @param ProductAvailability $productAvailability
     * @param VendorCategory $vendorCategory
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(ProductAvailability $productAvailability, VendorCategory $vendorCategory, SettingsRepository $settingsRepository)
    {
        $this->productAvailability = $productAvailability;
        $this->vendorCategory = $vendorCategory;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Update product publish.
     *
     * @param Product $product
     */
    public function updateProductPublish(Product $product)
    {
        $IsOnlyAvailableAllowed = $this->settingsRepository->getProperty('shop.show_available_products_only');

        // set publishing
        $product->published = (int)$this->isPublishingAllowed($product, $IsOnlyAvailableAllowed);
        $product->save();
    }

    /**
     * Update products publish.
     *
     * @param Collection $products
     */
    public function updateProductsPublish(Collection $products)
    {
        $IsOnlyAvailableAllowed = $this->settingsRepository->getProperty('shop.show_available_products_only');

        foreach ($products as $product) {

            Product::withoutSyncingToSearch(function () use ($product, $IsOnlyAvailableAllowed) {
                // set publishing
                $product->published = (int)$this->isPublishingAllowed($product, $IsOnlyAvailableAllowed);
                $product->save();
            });
        }
    }

    /**
     * @param Product $product
     * @param bool $IsOnlyAvailableAllowed
     * @return bool
     */
    private function isPublishingAllowed(Product $product, bool $IsOnlyAvailableAllowed):bool
    {
        return  (!$IsOnlyAvailableAllowed || $this->productAvailability->isProductAvailableOrExpecting($product)) && $this->checkProductProfit($product);
    }

    /**
     * Check product profit conditions.
     *
     * @param Product $product
     * @return bool
     */
    private function checkProductProfit(Product $product): bool
    {
        if (!$product->vendorProducts->count()){
            return true;
        }

        // calculate min vendor's offer price
        $minVendorsPrice = $product->vendorProducts->min('price');

        if ($product->price1 && $minVendorsPrice) {
            $vendorCategories = $this->vendorCategory->newQuery()->whereHas('vendorProducts', function ($query) use ($product) {
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

            return $maxProductProfitSum > $minProfitSumToPublish || $maxProductProfitPercents > $minProfitPercentsToPublish;
        } else {
            return true;
        }
    }
}
