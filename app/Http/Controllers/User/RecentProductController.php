<?php

namespace App\Http\Controllers\User;

use App\Models\FavouriteProduct;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RecentProduct;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\ProductPrice;
use Illuminate\Http\Request;
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
     * @var ProductPrice
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
     * @param ProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     */
    public function __construct(Product $product, ExchangeRates $exchangeRates, ProductPrice $productPrice, ProductAvailability $productAvailability)
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
            $recentProducts = $this->product->newQuery()->whereHas('recentProducts', function ($query) {
                $query->where('users_id', auth('web')->id());
            })->with('primaryImage')->get();

            $view = view('content.user.recent.registered.index');
        } else {
            $uuid = Cookie::get('uuid', Str::uuid());

            $recentProducts = $this->product->newQuery()->whereHas('recentProducts', function ($query) use ($uuid) {
                $query->where('uuid', $uuid);
            })->get();

            $view = view('content.user.recent.unregistered.index');
        }

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
            $product->price = $productPrice ? number_format($productPrice, 2, '.', ',') : null;
            $product->localPrice = ($productPrice && $exchangeRate) ? number_format($productPrice * $exchangeRate, 0, '.', ',') : null;

            // product storages
            $isProductAvailable = $this->productAvailability->getHavingProductStorages($product)->count();
            $product->isProductAvailable = $isProductAvailable;
            // product arrival time
            if (!$isProductAvailable) {
                $product->isProductExpected = (bool)$this->productAvailability->getProductAvailableTime($product);
            }
        }
    }
}
