<?php
/**
 * Category products creator.
 */

namespace App\Support\Shop\Products;


use Illuminate\Support\Collection;

class CartProducts extends AbstractCartProduct
{
    /**
     * Get products with its properties.
     *
     * @param $user
     * @return Collection
     */
    public function getProducts($user): Collection
    {
        $products = $user->cartProducts()->with('primaryImage')->get();

        $this->addProductsProperties($products, $user);

        return $products;
    }

    /**
     * Add properties to each product.
     *
     * @param Collection $products
     * @param $user
     */
    private function addProductsProperties(Collection $products, $user)
    {
        $exchangeRate = $this->exchangeRates->getRate();

        $currentLocale = app()->getLocale();
        $localeRouteParameter = $currentLocale === config('app.canonical_locale') ? null : $currentLocale;

        $productPriceColumn = 'price' . $user->price_group;

        foreach ($products as $product) {
            // add query parameters
            $this->createProductLinkUrl($product, $localeRouteParameter);

            // add product prices
            $this->createProductPrice($product, $exchangeRate, $productPriceColumn);
        }
    }
}
