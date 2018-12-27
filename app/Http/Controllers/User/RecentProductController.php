<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\UserProductPrice;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class RecentProductController extends Controller
{
    /**
     * @var Product
     */
    private $product;
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
     * FavouriteProductController constructor.
     * @param Product $product
     * @param ExchangeRates $exchangeRates
     * @param UserProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     */
    public function __construct(Product $product, ExchangeRates $exchangeRates, UserProductPrice $productPrice, ProductAvailability $productAvailability)
    {
        $this->product = $product;
        $this->exchangeRates = $exchangeRates;
        $this->productPrice = $productPrice;
        $this->productAvailability = $productAvailability;
    }

    /**
     *
     *
     * @return $this|string
     */
    public function index()
    {

        if (auth('web')->check()) {
            $recentProductsQuery = $this->product->newQuery()->whereHas('recentProducts', function ($query) {
                $query->where('users_id', auth('web')->id());
            });

            $view = view('content.user.recent.registered.index');
        } else {
            $uuid = Cookie::get('uuid', Str::uuid());

            $recentProductsQuery = $this->product->newQuery()->whereHas('recentProducts', function ($query) use ($uuid) {
                $query->where('uuid', $uuid);
            });

            $view = view('content.user.recent.unregistered.index');
        }

        $recentProducts = $recentProductsQuery->with('primaryImage', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts')
            ->get();

        $this->addProductsProperties($recentProducts);

        return $view->with(compact('recentProducts'));
    }

    /**
     * Add properties to products.
     *
     * @param Collection $products
     */
    private function addProductsProperties(Collection $products)
    {
        $exchangeRate = $this->exchangeRates->getRate();

        foreach ($products as $product) {
            // product prices
            $productPrice = $this->productPrice->getUsersProductPrice($product);
            $product->price = $productPrice ? $this->formatPrice($productPrice) : null;
            $product->localPrice = ($productPrice && $exchangeRate) ? $this->formatPrice($productPrice * $exchangeRate, 0) : null;

            // product availability
            $productExpectedAt = $this->productAvailability->getProductExpectedTime($product);
            $product->isAvailable = $this->productAvailability->isProductAvailable($product);
            $product->expectedAt = $productExpectedAt;
            $product->isExpectedToday = ($productExpectedAt && $productExpectedAt < Carbon::today()->addDay()) ? true : false;
        }
    }

    /**
     * Format product price.
     *
     * @param float $price
     * @param int $decimals
     * @return string
     */
    private function formatPrice(float $price, int $decimals = 2)
    {
        return number_format($price, $decimals, '.', ',');
    }
}
