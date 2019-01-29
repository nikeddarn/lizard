<?php
/**
 * Product properties creator.
 */

namespace App\Support\Shop\Products;

use App\Contracts\Shop\UrlParametersInterface;
use App\Support\Settings\SettingsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\UserProductPrice;
use Illuminate\Support\Collection;

abstract class ProductProperties
{
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
     * CategoryProductsCreator constructor.
     * @param ExchangeRates $exchangeRates
     * @param UserProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(ExchangeRates $exchangeRates, UserProductPrice $productPrice, ProductAvailability $productAvailability, SettingsRepository $settingsRepository)
    {

        $this->exchangeRates = $exchangeRates;
        $this->productPrice = $productPrice;
        $this->productAvailability = $productAvailability;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Add properties to each product.
     *
     * @param LengthAwarePaginator|Collection $products
     */
    protected function addProductsProperties($products)
    {
        $exchangeRate = $this->exchangeRates->getRate();

        foreach ($products as $product) {
            // add query parameters
            $product->href = $this->createProductLinkUrl($product->url);

            // product prices
            $productPrice = $this->productPrice->getUsersProductPrice($product);

            if ($this->isUsdPriceAllowedToShow() && $productPrice) {
                $product->price = $this->formatProductPrice($productPrice);
            }

            if ($productPrice && $exchangeRate) {
                $product->localPrice = $this->formatProductPrice($productPrice * $exchangeRate, 0);
            }

            // product availability
            $productExpectedAt = $this->productAvailability->getProductExpectedTime($product);
            $product->isAvailable = $this->productAvailability->isProductAvailable($product);
            $product->expectedAt = $productExpectedAt;
            $product->isExpectedToday = ($productExpectedAt && $productExpectedAt < Carbon::today()->addDay()) ? true : false;

            // defect rate
            if ($product->sold_quantity >= config('shop.min_quantity_to_show_rate.defect')) {
                $product->defectRate = $product->defect_rate;
            }

            // product rating
            if ($product->rating_quantity >= config('shop.min_quantity_to_show_rate.product')) {
                $product->productRate = $product->rating;
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
        $showUsdPriceSettings = $this->settingsRepository->getProperty('shop.show_usd_price');

        if ($showUsdPriceSettings['allowed']) {
            if ($showUsdPriceSettings['min_user_price_group'] === 1) {
                return true;
            } else {
                if (auth('web')->check()) {
                    $user = auth('web')->user();
                    return $user->price_group >= $showUsdPriceSettings['min_user_price_group'];
                } else {
                    return false;
                }
            }
        } else {
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
        return number_format($price, $decimals, '.', ' ');
    }

    /**
     * Create product's link url.
     *
     * @param string $productUrl
     * @return string
     */
    private function createProductLinkUrl(string $productUrl):string
    {
        $currentRouteLocaleParameter = request()->route()->parameter(UrlParametersInterface::LOCALE);

        $productRouteName = 'shop.product.index';

        return route($productRouteName, [
            'url' => $productUrl,
            'locale' => $currentRouteLocaleParameter,
        ]);
    }
}
