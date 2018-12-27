<?php

namespace App\Http\Controllers\User;

use App\Models\FavouriteProduct;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\UserProductPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class FavouriteProductController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var FavouriteProduct
     */
    private $favouriteProduct;
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
     * @param Request $request
     * @param FavouriteProduct $favouriteProduct
     * @param Product $product
     * @param ExchangeRates $exchangeRates
     * @param UserProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     */
    public function __construct(Request $request, FavouriteProduct $favouriteProduct, Product $product, ExchangeRates $exchangeRates, UserProductPrice $productPrice, ProductAvailability $productAvailability)
    {
        $this->request = $request;
        $this->favouriteProduct = $favouriteProduct;
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
            $favouriteProductsQuery = $this->product->newQuery()
                ->whereHas('favouriteProducts', function ($query) {
                    $query->where('users_id', auth('web')->id());
                });

            $view = view('content.user.favourite.registered.index');
        } else {
            $uuid = Cookie::get('uuid', Str::uuid());

            $favouriteProductsQuery = $this->product->newQuery()
                ->whereHas('favouriteProducts', function ($query) use ($uuid) {
                    $query->where('uuid', $uuid);
                });

            $view = view('content.user.favourite.unregistered.index');
        }

        $favouriteProducts = $favouriteProductsQuery->with('primaryImage', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts')
            ->get();

        $this->addProductsProperties($favouriteProducts);

        return $view->with(compact('favouriteProducts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response|bool
     */
    public function addProductToFavourite(string $id)
    {
        if (auth('web')->check()) {

            $usersId = auth('web')->id();

            $favouriteProduct = $this->favouriteProduct->newQuery()->where([
                ['products_id', '=', $id],
                ['users_id', '=', $usersId]
            ])->first();

            if ($favouriteProduct) {
                return $this->request->ajax() ? 0 : back();
            } else {
                $this->favouriteProduct->newQuery()->create([
                    'products_id' => $id,
                    'users_id' => $usersId,
                ]);

                return $this->request->ajax() ? 1 : back();
            }
        } else {

            $uuid = Cookie::get('uuid', Str::uuid());

            $favouriteProduct = $this->favouriteProduct->newQuery()->where([
                ['products_id', '=', $id],
                ['uuid', '=', $uuid]
            ])->first();

            if ($favouriteProduct) {
                return $this->request->ajax() ? 0 : back();
            } else {
                $this->favouriteProduct->newQuery()->create([
                    'products_id' => $id,
                    'uuid' => $uuid,
                ]);

                Cookie::queue(Cookie::forever('uuid', $uuid));

                return $this->request->ajax() ? 1 : back();
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function removeProductFromFavourite(string $id)
    {
        $product = $this->product->newQuery()->findOrFail($id);

        if (auth('web')->check()) {
            $product->favouriteProducts()->where('users_id', auth('web')->id())->delete();
        } else {
            $uuid = Cookie::get('uuid');

            $product->favouriteProducts()->where('uuid', $uuid)->delete();
        }

        return back();
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
