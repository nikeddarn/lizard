<?php
/**
 * Product properties creator.
 */

namespace App\Support\Shop\Products;

use App\Models\Product;
use Carbon\Carbon;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\UserProductPrice;

abstract class AbstractCartProduct
{
    /**
     * @var ExchangeRates
     */
    protected $exchangeRates;
    /**
     * @var UserProductPrice
     */
    protected $productPrice;
    /**
     * @var ProductAvailability
     */
    protected $productAvailability;

    /**
     * CategoryProductsCreator constructor.
     * @param ExchangeRates $exchangeRates
     * @param UserProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     */
    public function __construct(ExchangeRates $exchangeRates, UserProductPrice $productPrice, ProductAvailability $productAvailability)
    {
        $this->exchangeRates = $exchangeRates;
        $this->productPrice = $productPrice;
        $this->productAvailability = $productAvailability;
    }


    /**
     * Create product prices.
     *
     * @param Product $product
     * @param float $exchangeRate
     * @param $user
     */
    protected function createProductPrice(Product $product, float $exchangeRate, $user)
    {
        // product prices
        $productPrice = $this->productPrice->getUserProductPrice($product, $user);

        if ($productPrice && $exchangeRate) {
            $product->localPrice = $productPrice * $exchangeRate;
            $product->formattedLocalPrice = number_format($productPrice * $exchangeRate);
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
}
