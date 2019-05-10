<?php

namespace App\Http\Controllers\Sale;

use App\Contracts\Order\DeliveryTypesInterface;
use App\Http\Requests\Sale\UpdateOrderAddressRequest;
use App\Models\City;
use App\Models\DeliveryType;
use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Models\Storage;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderAddressController extends Controller
{
    /**
     * @var Order
     */
    private $order;
    /**
     * @var DeliveryType
     */
    private $deliveryType;
    /**
     * @var City
     */
    private $city;
    /**
     * @var Storage
     */
    private $storage;

    /**
     * OrderRecipientController constructor.
     * @param Order $order
     * @param DeliveryType $deliveryType
     * @param City $city
     * @param Storage $storage
     */
    public function __construct(Order $order, DeliveryType $deliveryType, City $city, Storage $storage)
    {
        $this->order = $order;
        $this->deliveryType = $deliveryType;
        $this->city = $city;
        $this->storage = $storage;
    }

    /**
     * Edit order product.
     *
     * @param int $orderId
     * @return View
     * @throws AuthorizationException
     */
    public function edit(int $orderId)
    {
        $order = $this->order->newQuery()->with('deliveryType', 'orderAddress', 'user')->findOrFail($orderId);

        $this->authorize('updateDelivery', $order);

        $locale = app()->getLocale();

        $user = $order->user;

        $lastUserAddress = $order->user->orderAddresses()->orderByDesc('id')->first();
        $lastSelfDeliveryOrder = $user->orders()->whereNotNull('storages_id')->orderByDesc('id')->first();

        $deliveryTypes = $this->deliveryType->newQuery()->get();
        $cities = $this->city->newQuery()->has('storages')->get();
        $storages = $this->storage->newQuery()->join('cities', 'storages.cities_id', '=', 'cities.id')->orderByRaw('cities.name_' . $locale)->select('storages.*')->with('city')->get();

        return view('content.sale.orders.edit.address.index')->with(compact('order', 'deliveryTypes', 'cities', 'lastUserAddress', 'storages', 'lastSelfDeliveryOrder'));
    }

    /**
     * @param UpdateOrderAddressRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateOrderAddressRequest $request)
    {
        $orderId = $request->get('order_id');
        $order = $this->order->newQuery()->with('orderAddress', 'user')->findOrFail($orderId);

        $this->authorize('updateDelivery', $order);

        $deliveryTypeId = (int)$request->get('delivery_type');

        // update order
        $order->delivery_types_id = $deliveryTypeId;
        $order->save();

        if ($deliveryTypeId === DeliveryTypesInterface::SELF){
            $order->order_addresses_id = null;
            $order->storages_id = (int)$request->get('storage_id');
            $order->save();
        }else{
            $orderAddressData = [
                'address' => $request->get('address'),
            ];

            if ($deliveryTypeId == DeliveryTypesInterface::COURIER) {
                $orderAddressData['cities_id'] = $request->get('city_id');
            }

            if ($order->orderAddress){
                // update order address
                $order->orderAddress->update($orderAddressData);
            }else{
                // create order address
                $orderAddressData['users_id'] = $order->user->id;
                $orderAddress = $order->orderAddress()->create($orderAddressData);

                $order->order_addresses_id = $orderAddress->id;
                $order->storages_id = null;
                $order->save();
            }
        }

        return redirect(route('admin.order.manage', ['order_id' => $orderId]));
    }
}
