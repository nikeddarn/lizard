<?php
/**
 * Product properties creator.
 */

namespace App\Support\Shop\Products;

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
     * @param $user
     */
    protected function addProductsProperties($products, $user)
    {
        $exchangeRate = $this->exchangeRates->getRate();

        $isUsdPriceAllowed = $this->isUsdPriceAllowedToShow();

        $currentLocale = app()->getLocale();
        $localeRouteParameter = $currentLocale === config('app.canonical_locale') ? null : $currentLocale;

        foreach ($products as $product) {
            // add query parameters
            $this->createProductLinkUrl($product, $localeRouteParameter);

            // add product prices
            $this->createProductPrice($product, $exchangeRate, $isUsdPriceAllowed, $user);

            // add product availability
            $this->createProductAvailability($product);

            // add product rating
            $this->createProductRates($product);

            // set product favourite
            $this->createFavouriteProperty($product);
        }
    }

    /**
     * Is USD price allowed to show for current user.
     *
     * @return bool
     */
    protected function isUsdPriceAllowedToShow(): bool
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
     * Create product prices.
     *
     * @param Product $product
     * @param float $exchangeRate
     * @param bool $isUsdPriceAllowed
     * @param $user
     */
    protected function createProductPrice(Product $product, float $exchangeRate, bool $isUsdPriceAllowed, $user)
    {
        // product prices
        $productPrice = $this->productPrice->getUserProductPrice($product, $user);

        if ($productPrice){
            if ($isUsdPriceAllowed) {
                $product->price = number_format($productPrice);
            }

            if ($exchangeRate) {
                $product->localPrice = number_format($productPrice * $exchangeRate);
            }
        }
    }

    /**
     * Create product availability.
     *
     * @param Product $product
     */
    protected function createProductAvailability(Product $product)
    {
        $productExpectedAt = $this->productAvailability->getProductExpectedTime($product);
        $product->isAvailable = $this->productAvailability->isProductAvailable($product);
        $product->expectedAt = $productExpectedAt;
        $product->isExpectedToday = ($productExpectedAt && $productExpectedAt < Carbon::today()->addDay()) ? true : false;
    }

    /**
     * Create product's link url.
     *
     * @param Product $product
     * @param string $locale
     */
    protected function createProductLinkUrl(Product $product, string $locale = null)
    {
        $product->href = route('shop.product.index', [
            'url' => $product->url,
            'locale' => $locale,
        ]);
    }

    /**
     * Create product rates.
     *
     * @param Product $product
     */
    protected function createProductRates(Product $product)
    {
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
    }

    /**
     * Create favourite property.
     *
     * @param Product $product
     */
    protected function createFavouriteProperty(Product $product)
    {
        $product->isFavourite = (bool)$product->favouriteProducts->count();
    }
}
