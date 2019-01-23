<?php

namespace App\Http\Controllers\User;

use App\Models\FavouriteProduct;
use App\Http\Controllers\Controller;
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
     * FavouriteProductController constructor.
     * @param Request $request
     * @param FavouriteProduct $favouriteProduct
     * @param FavouriteProducts $favouriteProducts
     */
    public function __construct(Request $request, FavouriteProduct $favouriteProduct, FavouriteProducts $favouriteProducts)
    {
        $this->request = $request;
        $this->favouriteProduct = $favouriteProduct;
        $this->favouriteProducts = $favouriteProducts;
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
     * @return \Illuminate\Http\Response|bool
     */
    public function addProductToFavourite(string $id)
    {
        if (auth('web')->check()) {
            // get user's id
            $usersId = auth('web')->id();

            // get product
            $favouriteProduct = $this->favouriteProduct->newQuery()->where([
                ['products_id', '=', $id],
                ['users_id', '=', $usersId],
            ])->first();

            if ($favouriteProduct) {
                return $this->request->ajax() ? null : back();
            } else {
                // add to favourite
                $this->favouriteProduct->newQuery()->firstOrCreate([
                    'products_id' => $id,
                    'users_id' => $usersId,
                ]);

                return $this->request->ajax() ? true : back();
            }
        } else {
            // get user's uuid
            $uuid = $this->request->cookie('uuid', Str::uuid());

            // restore cookie
            Cookie::queue(Cookie::forever('uuid', $uuid));

            // get product
            $favouriteProduct = $this->favouriteProduct->newQuery()->where([
                ['products_id', '=', $id],
                ['uuid', '=', $uuid],
            ])->first();

            if ($favouriteProduct) {
                return $this->request->ajax() ? '' : back();
            } else {
                // add to favourite
                $this->favouriteProduct->newQuery()->firstOrCreate([
                    'products_id' => $id,
                    'uuid' => $uuid,
                ]);
            }

            return $this->request->ajax() ? '1' : back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function removeProductFromFavourite(string $id)
    {
        $favouriteProductQuery = $this->favouriteProduct->newQuery()->where('products_id', $id);

        if (auth('web')->check()) {
            // get user's id
            $usersId = auth('web')->id();

            // constraint query
            $favouriteProductQuery->where('users_id', $usersId);
        } else {
            // get user's uuid
            $uuid = $this->request->cookie('uuid');

            // constraint query
            $favouriteProductQuery->where('uuid', $uuid);
        }

        $favouriteProductQuery->delete();

        return back();
    }
}
