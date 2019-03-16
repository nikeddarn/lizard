<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\Shop\InsertCartProductsRequest;
use App\Models\Product;
use App\Models\UserCartProduct;
use App\Support\Shop\Products\CartProducts;
use App\Support\Shop\Products\FullCartProducts;
use App\Support\User\RetrieveUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class CartController extends Controller
{
    use RetrieveUser;

    /**
     * Show user cart.
     *
     * @param FullCartProducts $cartProducts
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(FullCartProducts $cartProducts)
    {
        $user = $this->getOrCreateUser();

        $products = $cartProducts->getProducts($user);

        return view('content.shop.cart.index')->with($this->getCartData($products));

    }

    /**
     * Add product to cart.
     *
     * @param int $productId
     * @param CartProducts $cartProducts
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|string
     * @throws \Throwable
     */
    public function addProduct(int $productId, CartProducts $cartProducts)
    {
        $user = $this->getOrCreateUser();

        // add to cart
        $user->cartProducts()->whereNotNull('price1')->syncWithoutDetaching([$productId => [
            'count' => 1,
        ]]);

        if (request()->ajax()) {
            $products = $cartProducts->getProducts($user);
            return $this->createAjaxData($products);
        } else {
            return back();
        }
    }

    /**
     * Add products to cart with count.
     *
     * @param InsertCartProductsRequest $request
     * @param CartProducts $cartProducts
     * @return false|\Illuminate\Http\RedirectResponse|string
     * @throws \Throwable
     */
    public function addProductCount(InsertCartProductsRequest $request, CartProducts $cartProducts)
    {
        $productsId = $request->get('product_id');
        $productsCount = $request->get('count');

        $user = $this->getOrCreateUser();

        foreach ($productsId as $index => $productId) {
            $user->cartProducts()->whereNotNull('price1')->syncWithoutDetaching([$productId => [
                'count' => $productsCount[$index],
            ]]);
        }

        if (request()->ajax()) {
            $products = $cartProducts->getProducts($user);
            return $this->createAjaxData($products);
        } else {
            return back();
        }
    }

    /**
     * Delete product from cart.
     *
     * @param int $productId
     * @param CartProducts $cartProducts
     * @return false|\Illuminate\Http\RedirectResponse|string
     * @throws \Throwable
     */
    public function removeProduct(int $productId, CartProducts $cartProducts)
    {
        $user = $this->getOrCreateUser();

        $user->cartProducts()->detach($productId);

        if (request()->ajax()) {
            $products = $cartProducts->getProducts($user);
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
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws \Throwable
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
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws \Throwable
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
     * @throws \Throwable
     */
    private function createAjaxData(Collection $products)
    {
        $cartData = $this->getCartData($products);

        $cart = view('layouts.parts.headers.common.middle.parts.cart')->with([
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
