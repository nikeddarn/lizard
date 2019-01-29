<?php

namespace App\Http\Controllers\User;

use App\Models\FavouriteProduct;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\Shop\Products\FavouriteProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\View\View;

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
     * @var FavouriteProducts
     */
    private $favouriteProducts;
    /**
     * @var Product
     */
    private $product;

    /**
     * FavouriteProductController constructor.
     * @param Request $request
     * @param FavouriteProduct $favouriteProduct
     * @param FavouriteProducts $favouriteProducts
     * @param Product $product
     */
    public function __construct(Request $request, FavouriteProduct $favouriteProduct, FavouriteProducts $favouriteProducts, Product $product)
    {
        $this->request = $request;
        $this->favouriteProduct = $favouriteProduct;
        $this->favouriteProducts = $favouriteProducts;
        $this->product = $product;
    }

    /**
     * Show user'd favourite products.
     *
     * @return View
     */
    public function index()
    {
        if (auth('web')->check()) {
            $view = view('content.user.favourite.registered.index');
        } else {
            $view = view('content.user.favourite.unregistered.index');
        }

        $favouriteProducts = $this->favouriteProducts->getProducts();

        return $view->with(compact('favouriteProducts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string $id
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function addProductToFavourite(string $id)
    {
        $product = $this->product->newQuery()->findOrFail($id);

        if (auth('web')->check()) {
            $product->favouriteProducts()->create([
                'users_id' => auth('web')->id(),
            ]);
        } else {
            if ($this->request->hasCookie('uuid')) {
                // get user's uuid
                $uuid = $this->request->cookie('uuid', Str::uuid());
            } else {
                $uuid = Str::uuid();
            }

            // restore cookie
            Cookie::queue(Cookie::forever('uuid', $uuid));

            $product->favouriteProducts()->create([
                'uuid' => $uuid,
            ]);
        }

        // return link for remove product from favourite for ajax
        return $this->request->ajax() ? route('user.favourites.remove', ['id' => $id]) : back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws \Exception
     */
    public function removeProductFromFavourite(string $id)
    {
        $favouriteProductQuery = $this->favouriteProduct->newQuery()->where('products_id', $id);

        if (auth('web')->check()) {
            $favouriteProductQuery->where('users_id', auth('web')->id())->delete();
        } elseif ($this->request->hasCookie('uuid')) {
            $favouriteProductQuery->where('uuid', $this->request->cookie('uuid'))->delete();
        }

        // return link for add product to favourite for ajax
        return $this->request->ajax() ? route('user.favourites.add', ['id' => $id]) : back();
    }
}
