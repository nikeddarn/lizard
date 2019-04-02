<?php

namespace App\Http\Controllers\User;

use App\Contracts\Order\OrderStatusInterface;
use App\Events\Order\OrderCanceled;
use App\Events\Order\OrderUpdated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Support\Orders\DeliveryPrice;
use App\Support\User\RetrieveUser;
use Illuminate\Auth\Access\AuthorizationException;
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
     * OrderController constructor.
     * @param Order $order
     * @param OrderProduct $orderProduct
     * @param DeliveryPrice $deliveryPrice
     */
    public function __construct(Order $order, OrderProduct $orderProduct, DeliveryPrice $deliveryPrice)
    {
        $this->order = $order;
        $this->orderProduct = $orderProduct;
        $this->deliveryPrice = $deliveryPrice;
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

        if ($user) {
            $orders = $this->order->newQuery()->where('users_id', $user->id)->orderByDesc('id')->with('orderStatus', 'products.primaryImage')->paginate(config('shop.show_orders_per_page'));
        } else {
            $orders = null;
        }

        return view('content.user.orders.list.index')->with(compact('orders', 'locale'));
    }

    /**
     * Update user order.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request)
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
     * Cancel order
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

        DB::beginTransaction();

        $order->order_status_id = OrderStatusInterface::CANCELLED;
        $order->save();

        event(new OrderCanceled($order));

        DB::commit();

        return back();
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
}
