<?php
/**
 * Product properties creator.
 */

namespace App\Support\Shop\Products;

use App\Contracts\Shop\UrlParametersInterface;
use App\Models\Category;
use App\Models\Product;
use App\Support\Settings\SettingsRepository;
use App\Support\User\RetrieveUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\UserProductPrice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class AbstractProduct
{
    use RetrieveUser;

    /**
     * @var int
     */
    protected $productsPerPage;
    /**
     * @var ExchangeRates
     */
    private $exchangeRates;
    /**
     * @var UserProductPrice
     */
    private $productPrice;
    /**
     * @var ProductAvailability
     */
    private $productAvailability;
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;
    /**
     * @var Product
     */
    private $product;

    /**
     * CategoryProductsCreator constructor.
     * @param ExchangeRates $exchangeRates
     * @param UserProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     * @param SettingsRepository $settingsRepository
     * @param Product $product
     */
    public function __construct(ExchangeRates $exchangeRates, UserProductPrice $productPrice, ProductAvailability $productAvailability, SettingsRepository $settingsRepository, Product $product)
    {
        $this->exchangeRates = $exchangeRates;
        $this->productPrice = $productPrice;
        $this->productAvailability = $productAvailability;
        $this->settingsRepository = $settingsRepository;
        $this->product = $product;

        $this->productsPerPage = $settingsRepository->getProperty('shop.show_products_per_page');
    }

    /**
     * Get retrieve product Builder.
     *
     * @return Builder
     */
    protected function getRetrieveProductQuery():Builder
    {
        return $this->product->newQuery()->where('published', 1);
    }

    /**
     * Get retrieve product Builder.
     *
     * @param Category $category
     * @return Builder
     */
    protected function getRetrieveCategoryProductsQuery(Category $category):Builder
    {
        return $category->products()->getQuery()
            ->where('published', 1)
            ->where('is_archive', 0);
    }

    /**
     * Add properties to each product.
     *
     * @param LengthAwarePaginator|Collection $products
     */
    protected function addProductsProperties($products)
    {
        $exchangeRate = $this->exchangeRates->getRate();

        $isUsdPriceAllowed = $this->isUsdPriceAllowedToShow();

        foreach ($products as $product) {
            // add query parameters
            $product->href = $this->createProductLinkUrl($product->url);

            // product prices
            $productPrice = $this->productPrice->getUsersProductPrice($product);

            if ($productPrice){
                if ($isUsdPriceAllowed) {
                    $product->price = $this->formatProductPrice($productPrice);
                }

                if ($exchangeRate) {
                    $product->localPrice = $this->formatProductPrice($productPrice * $exchangeRate, 0);
                }
            }

            // product availability
            $productExpectedAt = $this->productAvailability->getProductExpectedTime($product);
            $product->isAvailable = $this->productAvailability->isProductAvailable($product);
            $product->expectedAt = $productExpectedAt;
            $product->isExpectedToday = ($productExpectedAt && $productExpectedAt < Carbon::today()->addDay()) ? true : false;

            // product rating
            $showRateConfig = $this->settingsRepository->getProperty('shop.show_rate');
            if ($showRateConfig['allowed'] && $product->rating_quantity >= $showRateConfig['count']) {
                $product->productRate = $product->rating;
            }

            // defect rate
            $showDefectRateConfig = $this->settingsRepository->getProperty('shop.show_defect_rate');
            if ($showDefectRateConfig['allowed'] && $product->sold_quantity >= $showDefectRateConfig['count']) {
                $product->defectRate = $product->defect_rate;
            }

            // is product favourite
            $product->isFavourite = $product->favouriteProducts->count();
        }
    }

    /**
     * Is USD price allowed to show for current user.
     *
     * @return bool
     */
    private function isUsdPriceAllowedToShow(): bool
    {
        // get settings
        $showUsdPriceSettings = $this->settingsRepository->getProperty('currencies.show_usd_price');

        if ($showUsdPriceSettings['allowed']){
            if (auth('web')->check()){
                return auth('web')->user()->price_group >= $showUsdPriceSettings['min_user_price_group'];
            }else{
                return $showUsdPriceSettings['min_user_price_group'] === 1;
            }
        }else{
            return false;
        }
    }

    /**
     * Format product price.
     *
     * @param float $price
     * @param int $decimals
     * @return string
     */
    private function formatProductPrice(float $price, int $decimals = 0)
    {
        return number_format($price, $decimals, '.', '');
    }

    /**
     * Create product's link url.
     *
     * @param string $productUrl
     * @return string
     */
    private function createProductLinkUrl(string $productUrl): string
    {
        $currentRouteLocaleParameter = request()->route()->parameter(UrlParametersInterface::LOCALE);

        $productRouteName = 'shop.product.index';

        return route($productRouteName, [
            'url' => $productUrl,
            'locale' => $currentRouteLocaleParameter,
        ]);
    }
}
