<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\Shop\InsertCartProductsRequest;
use App\Models\Product;
use App\Models\StaticPage;
use App\Models\UserCartProduct;
use App\Support\Shop\Products\CartProducts;
use App\Support\Shop\Products\FullCartProducts;
use App\Support\User\RetrieveUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use stdClass;
use Throwable;

class CartController extends Controller
{
    use RetrieveUser;
    /**
     * @var CartProducts
     */
    private $cartProducts;
    /**
     * @var StaticPage
     */
    private $staticPage;

    /**
     * CartController constructor.
     * @param CartProducts $cartProducts
     * @param StaticPage $staticPage
     */
    public function __construct(CartProducts $cartProducts, StaticPage $staticPage)
    {
        $this->cartProducts = $cartProducts;
        $this->staticPage = $staticPage;
    }

    /**
     * Show user cart.
     *
     * @param FullCartProducts $cartProducts
     * @return View
     */
    public function index(FullCartProducts $cartProducts)
    {
        $user = $this->getOrCreateUser();

        $products = $cartProducts->getProducts($user);

        $pageData = $this->staticPage->newQuery()->where('route', 'shop.cart.index')->first();

        return view('content.shop.cart.index')->with($this->getCartData($products))->with(compact('pageData'));

    }

    /**
     * Add product to cart.
     *
     * @param int $productId
     * @param string|null $locale
     * @return RedirectResponse|string
     * @throws Throwable
     */
    public function addProduct(int $productId, string $locale = null)
    {
        if ($locale){
            app()->setLocale($locale);
        }else{
            app()->setLocale(config('app.canonical_locale'));
        }

        $user = $this->getOrCreateUser();

        // add to cart
        $user->cartProducts()->whereNotNull('price1')->syncWithoutDetaching([$productId => [
            'count' => 1,
        ]]);

        // add to recent
        $user->recentProducts()->syncWithoutDetaching([$productId]);

        if (request()->ajax()) {
            $products = $this->cartProducts->getProducts($user);
            return $this->createAjaxData($products);
        } else {
            return back();
        }
    }

    /**
     * Add products to cart with count.
     *
     * @param InsertCartProductsRequest $request
     * @return RedirectResponse|string
     * @throws Throwable
     */
    public function addProductCount(InsertCartProductsRequest $request)
    {
        $productsId = $request->get('product_id');
        $productsCount = $request->get('count');

        if ($request->has('locale')){
            app()->setLocale($request->get('locale'));
        }

        $user = $this->getOrCreateUser();

        foreach ($productsId as $index => $productId) {
            // add to cart
            $user->cartProducts()->whereNotNull('price1')->syncWithoutDetaching([$productId => [
                'count' => $productsCount[$index],
            ]]);

            // add to recent
            $user->recentProducts()->syncWithoutDetaching([$productId]);
        }

        if (request()->ajax()) {
            $products = $this->cartProducts->getProducts($user);
            return $this->createAjaxData($products);
        } else {
            return back();
        }
    }

    /**
     * Delete product from cart.
     *
     * @param int $productId
     * @return RedirectResponse|string
     * @throws Throwable
     */
    public function removeProduct(int $productId)
    {
        $user = $this->getOrCreateUser();

        $user->cartProducts()->detach($productId);

        if (request()->ajax()) {
            $products = $this->cartProducts->getProducts($user);
            return $this->createAjaxData($products);
        } else {
            return back();
        }
    }

    /**
     * Increase count of cart product.
     *
     * @param int $productId
     * @param FullCartProducts $cartProducts
     * @param UserCartProduct $userCartProduct
     * @return RedirectResponse|string
     * @throws Throwable
     */
    public function increaseProductCount(int $productId, FullCartProducts $cartProducts, UserCartProduct $userCartProduct)
    {
        $user = $this->getOrCreateUser();

        // increase cart quantity
        $userCartProduct->newQuery()
            ->where([
                ['users_id', '=', $user->id],
                ['products_id', '=', $productId],
            ])
            ->increment('count');

        if (request()->ajax()) {
            $products = $cartProducts->getProducts($user);

            return view('content.shop.cart.parts.body')->with($this->getCartData($products))->render();
        } else {
            return back();
        }
    }

    /**
     * Decrease count of cart product.
     *
     * @param int $productId
     * @param FullCartProducts $cartProducts
     * @param UserCartProduct $userCartProduct
     * @return RedirectResponse|string
     * @throws Throwable
     */
    public function decreaseProductCount(int $productId, FullCartProducts $cartProducts, UserCartProduct $userCartProduct)
    {
        $user = $this->getOrCreateUser();

        // increase cart quantity
        $userCartProduct->newQuery()
            ->where([
                ['users_id', '=', $user->id],
                ['products_id', '=', $productId],
            ])
            ->update(['count' => DB::raw('GREATEST(count - 1, 1)')]);

        if (request()->ajax()) {
            $products = $cartProducts->getProducts($user);

            return view('content.shop.cart.parts.body')->with($this->getCartData($products))->render();
        } else {
            return back();
        }
    }

    /**
     * Create data for ajax response.
     *
     * @param Collection $products
     * @return false|string
     * @throws Throwable
     */
    private function createAjaxData(Collection $products)
    {
        $locale = app()->getLocale();

        $cartData = $this->getCartData($products);

        $cart = view($locale . '.layouts.parts.headers.common.middle.parts.cart')->with([
            'cartData' => $cartData,
        ])->render();

        $responseData = new stdClass();

        $responseData->cart = $cart;
        $responseData->itemsCount = $cartData['products']->count();

        return json_encode($responseData);
    }

    /**
     * Get user cart data.
     *
     * @param Collection $products
     * @return array
     */
    private function getCartData(Collection $products)
    {
        // calculate product's prices and total amount
        $amount = number_format($products->sum(function (Product $product) {
            return $product->localPrice * $product->pivot->count;
        }));

        return compact('products', 'amount');
    }
}
