<?php

namespace App\Http\Controllers\User;

use App\Models\FavouriteProduct;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\Shop\Products\FavouriteProducts;
use App\Support\User\RetrieveUser;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavouriteProductController extends Controller
{
    use RetrieveUser;

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
        $user = $this->getOrCreateUser();

        $user->favouriteProducts()->syncWithoutDetaching([$id]);

        return $this->request->ajax() ? 'true' : back();
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
        $user = $this->getUser();

        if ($user) {
            $user->favouriteProducts()->detach($id);
        }

        return $this->request->ajax() ? 'true' : back();
    }
}
