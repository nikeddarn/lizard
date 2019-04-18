<?php

namespace App\Http\Controllers\User;

use App\Contracts\Order\DeliveryTypesInterface;
use App\Contracts\Order\OrderStatusInterface;
use App\Events\Order\OrderCanceled;
use App\Events\Order\OrderUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\UpdateOrderDeliveryRequest;
use App\Http\Requests\Shop\UpdateOrderProductsRequest;
use App\Models\City;
use App\Models\DeliveryType;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Support\Orders\DeliveryPrice;
use App\Support\User\RetrieveUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    use RetrieveUser;

    /**
     * @var Order
     */
    private $order;
    /**
     * @var OrderProduct
     */
    private $orderProduct;
    /**
     * @var DeliveryPrice
     */
    private $deliveryPrice;
    /**
     * @var DeliveryType
     */
    private $deliveryType;
    /**
     * @var City
     */
    private $city;

    /**
     * OrderController constructor.
     * @param Order $order
     * @param OrderProduct $orderProduct
     * @param DeliveryPrice $deliveryPrice
     * @param DeliveryType $deliveryType
     * @param City $city
     */
    public function __construct(Order $order, OrderProduct $orderProduct, DeliveryPrice $deliveryPrice, DeliveryType $deliveryType, City $city)
    {
        $this->order = $order;
        $this->orderProduct = $orderProduct;
        $this->deliveryPrice = $deliveryPrice;
        $this->deliveryType = $deliveryType;
        $this->city = $city;
    }

    /**
     * Show list of orders.
     *
     * @return View
     */
    public function index()
    {
        $user = $this->getUser();

        $locale = app()->getLocale();

        $deliveryTypes = $this->deliveryType->newQuery()->get();

        $cities = $this->city->newQuery()->has('storages')->get();

        if ($user) {
            $orders = $this->getUserOrders($user);
            $lastUserAddress = $user->orderAddresses()->orderByDesc('id')->first();
        } else {
            $orders = null;
            $lastUserAddress = null;
        }

        return view('content.user.orders.list.index')->with(compact('orders', 'locale', 'cities', 'deliveryTypes', 'lastUserAddress'));
    }

    /**
     * Update user order.
     *
     * @param UpdateOrderProductsRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateOrderProductsRequest $request)
    {
        $orderId = $request->get('order_id');
        $order = $this->order->newQuery()->with('orderProducts', 'user')->findOrFail($orderId);

        $this->authorize('update', $order);

        $updatedProductsIds = $request->get('product_id');
        $updatedProductsCount = $request->get('count');

        if (is_array($updatedProductsIds) && is_array($updatedProductsCount)) {

            DB::beginTransaction();
            $this->updateOrderProducts($order, $updatedProductsIds, $updatedProductsCount);
            $this->updateOrderAmounts($order);
            $this->setHandlingOrderStatus($order);

            event(new OrderUpdated($order));
            DB::commit();

        } else {
            $order->update([
                'order_status_id' => OrderStatusInterface::CANCELLED,
            ]);

            event(new OrderCanceled($order));
        }

        return back();
    }

    /**
     * Update order delivery.
     *
     * @param UpdateOrderDeliveryRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function updateDelivery(UpdateOrderDeliveryRequest $request)
    {
        $orderId = $request->get('order_id');
        $order = $this->order->newQuery()->with('orderAddress', 'orderRecipient', 'user')->findOrFail($orderId);

        $this->authorize('updateDelivery', $order);

        $deliveryTypeId = (int)$request->get('delivery_type');

        // update order
        $order->delivery_types_id = $deliveryTypeId;
        $order->save();

        // update order recipient
        $order->orderRecipient->update([
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
        ]);

        if ($deliveryTypeId === DeliveryTypesInterface::SELF){
            $order->order_addresses_id = null;
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
                $order->save();
            }
        }

        return back();
    }

    /**
     * Cancel order.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function cancel(Request $request)
    {
        $orderId = $request->get('order_id');
        $order = $this->order->newQuery()->findOrFail($orderId);

        $this->authorize('cancel', $order);

        $order->order_status_id = OrderStatusInterface::CANCELLED;
        $order->save();

        event(new OrderCanceled($order));

        return back();
    }

    /**
     * Get user's orders.
     *
     * @param $user
     * @return LengthAwarePaginator
     */
    private function getUserOrders($user): LengthAwarePaginator
    {
        $orders = $this->order->newQuery()->where('users_id', $user->id)->orderByDesc('id')->with('orderStatus', 'products.primaryImage', 'deliveryType', 'orderAddress', 'orderRecipient')->paginate(config('shop.show_orders_per_page'));

        foreach ($orders as $order) {
            $order->courierDeliverySum = $this->deliveryPrice->calculateDeliveryPrice($user, $order->products_sum);
        }

        return $orders;
    }

    /**
     * Update order products count.
     *
     * @param Order|Model $order
     * @param array $updatedProductsIds
     * @param array $updatedProductsCount
     */
    private function updateOrderProducts(Order $order, array $updatedProductsIds, array $updatedProductsCount)
    {
        $orderId = $order->id;
        $orderProducts = $order->orderProducts->keyBy('products_id');

        for ($i = 0; $i < count($updatedProductsIds); $i++) {
            $productId = $updatedProductsIds[$i];

            $oldProductCount = (int)$orderProducts->get($productId)->count;
            $newProductCount = (int)$updatedProductsCount[$i];

            // update order product quantity
            if ($oldProductCount !== $newProductCount) {
                $this->orderProduct->newQuery()->where([
                    ['orders_id', $orderId],
                    ['products_id', $productId],
                ])->update([
                    'count' => $newProductCount,
                ]);
            }

            // remove product from collection
            $orderProducts->forget($productId);
        }

        // delete order products that not present in form
        $removedOrderProductsIds = $orderProducts->keys();

        $this->orderProduct->newQuery()
            ->where('orders_id', $orderId)
            ->whereIn('products_id', $removedOrderProductsIds)
            ->delete();
    }

    /**
     * Update order data.
     *
     * @param Order|Model $order
     */
    private function updateOrderAmounts(Order $order)
    {
        $user = $order->user;
        $productsUahSum = 0;

        // reread updated order products
        $orderProducts = $order->orderProducts()->get();

        foreach ($orderProducts as $orderProduct) {
            $productsUahSum += $orderProduct->price * $orderProduct->count;
        }

        $deliverySum = $this->deliveryPrice->calculateDeliveryPrice($user, $productsUahSum);
        $totalSum = $productsUahSum + $deliverySum;

        $order->update([
            'products_sum' => $productsUahSum,
            'delivery_sum' => $deliverySum,
            'total_sum' => $totalSum,
        ]);
    }

    /**
     * Set 'handling' status.
     *
     * @param Order|Model $order
     */
    private function setHandlingOrderStatus(Order $order)
    {
        $order->update([
            'order_status_id' => OrderStatusInterface::HANDLING,
        ]);
    }
}
