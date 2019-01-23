<?php
/**
 * User recent products.
 */

namespace App\Support\Shop\Products;


use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\UserProductPrice;
use App\Support\Settings\SettingsRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class RecentProducts extends ProductProperties
{
    /**
     * @var Product
     */
    private $product;

    /**
     * FavouriteProducts constructor.
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

    /**
     * Get users' recent products.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getProducts()
    {
        if (auth('web')->check()) {
            $recentProductsQuery = $this->makeAuthenticatedUserProductsQuery();
        } else {
            $recentProductsQuery = $this->makeGuestUserProductsQuery();
        }

        $products = $recentProductsQuery->with('primaryImage', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts')
            ->paginate(config('shop.show_items_per_page'))->appends(request()->query());

        $this->addProductsProperties($products);

        return $products;
    }

    /**
     * Create retrieve authenticated user favourite products query.
     *
     * @return Builder
     */
    private function makeAuthenticatedUserProductsQuery(): Builder
    {
        $usersId = auth('web')->id();

        return $this->product->newQuery()
            ->whereHas('recentProducts', function ($query) use ($usersId) {
                $query->where('users_id', $usersId);
            });
    }

    /**
     * Create retrieve guest user favourite products query.
     *
     * @return Builder
     */
    private function makeGuestUserProductsQuery(): Builder
    {
        $uuid = Cookie::get('uuid', Str::uuid());

        return $this->product->newQuery()
            ->whereHas('recentProducts', function ($query) use ($uuid) {
                $query->where('uuid', $uuid);
            });
    }
}
