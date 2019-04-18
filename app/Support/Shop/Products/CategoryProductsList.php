<?php
/**
 * Category products creator.
 */

namespace App\Support\Shop\Products;


use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CategoryProductsList
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var ProductAvailability
     */
    private $productAvailability;
    /**
     * @var ExchangeRates
     */
    private $exchangeRates;

    /**
     * CategoryProductsList constructor.
     * @param Product $product
     * @param ProductAvailability $productAvailability
     * @param ExchangeRates $exchangeRates
     */
    public function __construct(Product $product, ProductAvailability $productAvailability, ExchangeRates $exchangeRates)
    {
        $this->product = $product;
        $this->productAvailability = $productAvailability;
        $this->exchangeRates = $exchangeRates;
    }

    /**
     * Get products with its properties.
     *
     * @param int $categoryId
     * @param $user
     * @param array $expectedProductsIds
     * @return Collection
     */
    public function getProducts(int $categoryId, $user, array $expectedProductsIds): Collection
    {
        $locale = app()->getLocale();

        $products = $this->product->newQuery()
            ->whereHas('categoryProducts', function ($query) use ($categoryId, $expectedProductsIds) {
                $query->where('categories_id', $categoryId);
            })
            ->whereNotIn('id', $expectedProductsIds)
            ->orderBy('name_' .$locale)
            ->with('availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts')
            ->get();

        $this->addProductsProperties($products, $user);

        return $products;
    }

    /**
     * Add properties to each product.
     *
     * @param Collection $products
     * @param $user
     */
    protected function addProductsProperties($products, $user)
    {
        $exchangeRate = $this->exchangeRates->getRate();

        $productPriceColumn = 'price' . $user->price_group;

        foreach ($products as $product) {
            // add product prices
            $productPrice = $product->$productPriceColumn;
            $product->price = number_format($productPrice);
            $product->localPrice = number_format($productPrice * $exchangeRate);

            // add product availability
            $this->createProductAvailability($product);

            $product->cartAble = $product->price1 && $product->published && ($product->isAvailable || $product->isExpectedToday || $product->isExpectedTomorrow || $product->expectedAt);
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

        if (!$isProductAvailable){
            $productExpectedAt = $this->productAvailability->getProductExpectedTime($product);

            if ($productExpectedAt){
                $product->isExpectedToday = ($productExpectedAt < Carbon::today()->addDay()) ? true : false;
                $product->isExpectedTomorrow = (!$product->isExpectedToday && $productExpectedAt < Carbon::today()->addDays(2)) ? true : false;
                $product->expectedAt = $productExpectedAt;
            }
        }
    }
}
