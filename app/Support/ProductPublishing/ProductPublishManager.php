<?php
/**
 * Update product publishing.
 */

namespace App\Support\ProductPublishing;


use App\Models\Product;
use App\Models\VendorCategory;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\Settings\SettingsRepository;

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
     * @var Product
     */
    private $product;

    /**
     * ProductPublishManager constructor.
     * @param ProductAvailability $productAvailability
     * @param VendorCategory $vendorCategory
     * @param SettingsRepository $settingsRepository
     * @param Product $product
     */
    public function __construct(ProductAvailability $productAvailability, VendorCategory $vendorCategory, SettingsRepository $settingsRepository, Product $product)
    {
        $this->productAvailability = $productAvailability;
        $this->vendorCategory = $vendorCategory;
        $this->settingsRepository = $settingsRepository;
        $this->product = $product;
    }

    /**
     * Update publish for all products.
     */
    public function updateAllProductsPublish()
    {
        $products = $this->product->newQuery()
            ->select(['id', 'price1', 'price2', 'price3'])
            ->with('availableOrExpectingVendorProducts', 'availableOrExpectingStorageProducts')
            ->with(['vendorProducts' => function ($query) {
                $query->select(['id', 'products_id', 'price'])->with('vendorCategories');
            }])
            ->get()
            ->keyBy('id');

        $showUnavailableProducts = $this->settingsRepository->getProperty('shop.show_unavailable_products');

        $publishedProductsIds = [];
        $unpublishedProductsIds = [];

        foreach ($products as $product) {
            if ($this->isPublishingAllowed($product, $showUnavailableProducts) && $this->checkProductProfit($product)) {
                $publishedProductsIds[] = $product->id;
            } else {
                $unpublishedProductsIds[] = $product->id;
            }
        }

        Product::withoutSyncingToSearch(function () use ($publishedProductsIds, $unpublishedProductsIds) {
            // set products published
            Product::query()->whereIn('id', $publishedProductsIds)->update([
                'published' => 1,
            ]);
            // set products unpublished
            Product::query()->whereIn('id', $unpublishedProductsIds)->update([
                'published' => 0,
            ]);
        });
    }

    /**
     * Update product publish.
     *
     * @param Product $product
     */
    public function updateProductPublish(Product $product)
    {
        $showUnavailableProducts = $this->settingsRepository->getProperty('shop.show_unavailable_products');

        // set publishing
        $product->published = (int)($this->isPublishingAllowed($product, $showUnavailableProducts) && $this->checkProductProfit($product));
        $product->save();
    }

    /**
     * @param Product $product
     * @param array $showUnavailableProducts
     * @return bool
     */
    private function isPublishingAllowed(Product $product, array $showUnavailableProducts): bool
    {
        return $this->productAvailability->isProductAvailableOrExpecting($product) || ($product->vendorProducts->count() && $showUnavailableProducts['vendor']) || (!$product->vendorProducts->count() && $showUnavailableProducts['own']);
    }

    /**
     * Check product profit conditions.
     *
     * @param Product $product
     * @return bool
     */
    private function checkProductProfit(Product $product): bool
    {
        if (!$product->vendorProducts->count()) {
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
