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
     * @var Category
     */
    private $category;

    /**
     * CategoryProductsCreator constructor.
     * @param ExchangeRates $exchangeRates
     * @param ProductAvailability $productAvailability
     * @param SettingsRepository $settingsRepository
     * @param Product $product
     * @param Category $category
     */
    public function __construct(ExchangeRates $exchangeRates, ProductAvailability $productAvailability, SettingsRepository $settingsRepository, Product $product, Category $category)
    {
        $this->exchangeRates = $exchangeRates;
        $this->productAvailability = $productAvailability;
        $this->settingsRepository = $settingsRepository;
        $this->product = $product;
        $this->category = $category;

        $this->productsPerPage = $settingsRepository->getProperty('shop.show_products_per_page');
    }

    /**
     * Get retrieve product Builder.
     *
     * @return Builder
     */
    protected function getRetrieveProductQuery(): Builder
    {
        return $this->product->newQuery();
    }

    /**
     * Get retrieve category Builder.
     *
     * @return Builder
     */
    protected function getRetrieveCategoryQuery(): Builder
    {
        return $this->category->newQuery();
    }

    /**
     * Get retrieve product Builder.
     *
     * @param Category $category
     * @return Builder
     */
    protected function getRetrieveCategoryProductsQuery(Category $category): Builder
    {
        return $category->products()->getQuery()
            ->where([
                ['published', '=', 1],
                ['is_archive', '=', 0],
            ]);
    }

    /**
     * Add properties to each product.
     *
     * @param LengthAwarePaginator|Collection $products
     * @param $user
     */
    protected function addProductsProperties($products, $user = null)
    {
        $exchangeRate = $this->exchangeRates->getRate();

        $isUsdPriceAllowed = $this->isUsdPriceAllowedToShow();

        $productPriceColumn = 'price' . ($user ? $user->price_group : 1);

        $currentLocale = app()->getLocale();
        $localeRouteParameter = $currentLocale === config('app.canonical_locale') ? null : $currentLocale;

        foreach ($products as $product) {
            // add query parameters
            $this->createProductLinkUrl($product, $localeRouteParameter);

            // add product prices
            $this->createProductPrice($product, $exchangeRate, $isUsdPriceAllowed, $productPriceColumn);

            // add product availability
            $this->createProductAvailability($product);

            // add product rating
            $this->createProductRates($product);

            // set product favourite
            $this->createFavouriteProperty($product);

            // is allow to put product to cart
            $this->setCartAbleProperty($product);
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

        if ($showUsdPriceSettings['allowed']) {
            if (auth('web')->check()) {
                return auth('web')->user()->price_group >= $showUsdPriceSettings['min_user_price_group'];
            } else {
                return $showUsdPriceSettings['min_user_price_group'] === 1;
            }
        } else {
            return false;
        }
    }

    /**
     * Create product prices.
     *
     * @param Product $product
     * @param float $exchangeRate
     * @param bool $isUsdPriceAllowed
     * @param string $productPriceColumn
     */
    protected function createProductPrice(Product $product, float $exchangeRate, bool $isUsdPriceAllowed, string $productPriceColumn)
    {
        // product prices
        $productPrice = $product->$productPriceColumn;

        if ($productPrice) {
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
        $isProductAvailable = $this->productAvailability->isProductAvailable($product);
        $product->isAvailable = $isProductAvailable;

        if (!$isProductAvailable) {
            $productExpectedAt = $this->productAvailability->getProductExpectedTime($product);

            if ($productExpectedAt) {
                $product->isExpectedToday = ($productExpectedAt < Carbon::today()->addDay()) ? true : false;
                $product->isExpectedTomorrow = (!$product->isExpectedToday && $productExpectedAt < Carbon::today()->addDays(2)) ? true : false;
                $product->expectedAt = $productExpectedAt;
            }
        }
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
            $product->productRateCount = trans_choice('shop.product.reviews', $product->defect_quantity);;
        }

        // defect rate
        $showDefectRateConfig = $this->settingsRepository->getProperty('shop.show_defect_rate');
        if ($showDefectRateConfig['allowed'] && $product->sold_quantity >= $showDefectRateConfig['count']) {
            $product->defectRate = $product->defect_rate;
            $product->defectRateCount = trans_choice('shop.product.defect', $product->defect_quantity);
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

    /**
     * Set is product cart able?
     *
     * @param Product $product
     */
    private function setCartAbleProperty(Product $product)
    {
        $product->cartAble = $product->price1 && $product->published && ($product->isAvailable || $product->isExpectedToday || $product->isExpectedTomorrow || $product->expectedAt);
    }
}
