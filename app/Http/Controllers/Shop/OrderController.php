<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\DeliveryType;
use App\Models\Product;
use App\Support\Orders\DeliveryPrice;
use App\Support\Shop\Products\CartProducts;
use App\Support\User\RetrieveUser;

class OrderController extends Controller
{
    use RetrieveUser;

    /**
     * Show checkout form.
     *
     * @param DeliveryType $deliveryType
     * @param City $city
     * @param CartProducts $cartProducts
     * @param DeliveryPrice $deliveryPrice
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(DeliveryType $deliveryType, City $city, CartProducts $cartProducts, DeliveryPrice $deliveryPrice)
    {
        $user = $this->getOrCreateUser();

        $lastUserAddress = $user->userAddresses()->orderByDesc('id')->first();

        $products = $cartProducts->getProducts($user);

        $cartProductsCount = $products->count();

        // calculate product's total amount
        $amount = round($products->sum(function (Product $product) {
            return $product->localPrice * $product->pivot->count;
        }));

        $formattedAmount = number_format($amount);

        // calculate delivery sum
        $courierDeliverySum = $deliveryPrice->calculateDeliveryPrice($user, $amount);

        $deliveryTypes = $deliveryType->newQuery()->get();

        $cities = $city->newQuery()->has('storages')->get();

        return view('content.shop.checkout.index')->with(compact('user', 'lastUserAddress', 'deliveryTypes', 'cartProductsCount', 'cities', 'amount', 'formattedAmount', 'courierDeliverySum'));
    }

    public function store()
    {

    }
}
