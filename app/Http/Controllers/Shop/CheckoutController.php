<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\DeliveryType;
use App\Support\User\RetrieveUser;

class CheckoutController extends Controller
{
    use RetrieveUser;

    /**
     * Show checkout form.
     *
     * @param DeliveryType $deliveryType
     * @param City $city
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(DeliveryType $deliveryType, City $city)
    {
        $user = $this->getOrCreateUser();

        $cartProductsCount = $user->userCartProducts()->count();

        $deliveryTypes = $deliveryType->newQuery()->get();

        $cities = $city->newQuery()->has('storages')->get();

        return view('content.shop.checkout.index')->with(compact('user', 'deliveryTypes', 'cartProductsCount', 'cities'));
    }
}
