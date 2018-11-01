<?php

namespace App\Http\Controllers\User;

use App\Models\FavouriteProduct;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\ProductPrice;
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
     * @var ProductPrice
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
     * @param ProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     */
    public function __construct(Request $request, FavouriteProduct $favouriteProduct, Product $product, ExchangeRates $exchangeRates, ProductPrice $productPrice, ProductAvailability $productAvailability)
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
            $favouriteProducts = $this->product->newQuery()->whereHas('favouriteProducts', function ($query) {
                $query->where('users_id', auth('web')->id());
            })->with('primaryImage')->get();

            $view = view('content.user.favourite.registered.index');
        } else {
            $uuid = Cookie::get('uuid', Str::uuid());

            $favouriteProducts = $this->product->newQuery()->whereHas('favouriteProducts', function ($query) use ($uuid) {
                $query->where('uuid', $uuid);
            })->get();

            $view = view('content.user.favourite.unregistered.index');
        }

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
            $productPrice = $this->productPrice->getPrice($product);
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
