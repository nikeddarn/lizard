<?php
/**
 * Create single product data.
 */

namespace App\Support\Shop\Products;


use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\UserProductPrice;
use App\Support\Settings\SettingsRepository;
use Illuminate\Database\Eloquent\Builder;

class SingleProductCreator extends ProductProperties
{
    /**
     * @var Product
     */
    private $product;

    /**
     * SingleProductCreator constructor.
     * @param ExchangeRates $exchangeRates
     * @param UserProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     * @param Product $product
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(ExchangeRates $exchangeRates, UserProductPrice $productPrice, ProductAvailability $productAvailability, Product $product, SettingsRepository $settingsRepository)
    {
        parent::__construct($exchangeRates, $productPrice, $productAvailability, $settingsRepository);
        $this->product = $product;
    }

    public function getProduct(string $url)
    {
        $query = $this->makeRetrieveProductsQuery($url);

        $query = $this->addRelations($query);

        $products = $query->get();

        $this->addProductsProperties($products);

        return $products->first();
    }

    /**
     * Make query.
     *
     * @param string $url
     * @return Builder
     */
    protected function makeRetrieveProductsQuery(string $url): Builder
    {
        return $this->product->newQuery()->where('url', $url);
    }

    /**
     * Add relations to query.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function addRelations(Builder $query): Builder
    {
        return $query->with('productImages', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts', 'availableProductStorages.city', 'attributeValues.attribute');
    }
}
