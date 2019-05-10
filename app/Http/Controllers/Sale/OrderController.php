<?php

namespace App\Http\Controllers\Sale;

use App\Contracts\Order\OrderStatusInterface;
use App\Events\Order\OrderCanceledByManager;
use App\Models\DeliveryType;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Support\Orders\OrderManagerBroker;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * @var Order
     */
    private $order;
    /**
     * @var OrderStatus
     */
    private $orderStatus;
    /**
     * @var DeliveryType
     */
    private $deliveryType;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var OrderManagerBroker
     */
    private $orderManagerBroker;

    /**
     * OrderController constructor.
     * @param Request $request
     * @param Order $order
     * @param OrderStatus $orderStatus
     * @param DeliveryType $deliveryType
     * @param OrderManagerBroker $orderManagerBroker
     */
    public function __construct(Request $request, Order $order, OrderStatus $orderStatus, DeliveryType $deliveryType, OrderManagerBroker $orderManagerBroker)
    {
        $this->order = $order;
        $this->orderStatus = $orderStatus;
        $this->deliveryType = $deliveryType;
        $this->request = $request;
        $this->orderManagerBroker = $orderManagerBroker;
    }

    /**
     * Show orders.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewList', $this->order);

        $orders = $this->getOrders();

        $orderStatusTypes = $this->orderStatus->newQuery()->get();
        $deliveryTypes = $this->deliveryType->newQuery()->get();

        return view('content.sale.orders.list.index')->with(compact('orders', 'orderStatusTypes', 'deliveryTypes'));
    }

    /**
     * Show order.
     *
     * @param int $order_id
     * @return View
     * @throws AuthorizationException
     */
    public function show(int $order_id)
    {
        $order = $this->order->newQuery()->with('orderStatus', 'deliveryType', 'orderAddress.city', 'orderRecipient', 'products')->findOrFail($order_id);

        $this->authorize('view', $order);

        $locale = app()->getLocale();

        return view('content.sale.orders.show.index')->with(compact('order', 'locale'));
    }

    /**
     * Manage order.
     *
     * @param int $order_id
     * @return View
     * @throws AuthorizationException
     */
    public function manage(int $order_id)
    {
        $order = $this->order->newQuery()->with('deliveryType', 'orderAddress.city', 'orderRecipient', 'products', 'storage.city')->findOrFail($order_id);

        $this->authorize('manage', $order);

        $this->setHandlingOrderStatus($order);

        $manager = auth('web')->user();


        $locale = app()->getLocale();

        //open session for given manager
        $this->orderManagerBroker->beginOrderManagerSession($order, $manager);

        return view('content.sale.orders.manage.index')->with(compact('order', 'locale'));
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

        event(new OrderCanceledByManager($order));

        return back();
    }

    /**
     * Get filtered orders.
     *
     * @return LengthAwarePaginator
     */
    private function getOrders()
    {
        $query = $this->order->newQuery()->with('orderStatus', 'deliveryType', 'currentActiveOrderManager.manager', 'currentNotifiedOrderManager.manager');

        if ($this->request->has('createdAt')) {
            if ($this->request->get('createdAt') === 'asc') {
                $query->orderBy('created_at');
            } elseif ($this->request->get('createdAt') === 'desc') {
                $query->orderByDesc('created_at');
            }
        } else {
            $query->orderByDesc('created_at');
        }

        if ($this->request->has('deliveryType')) {
            $query->where('delivery_types_id', $this->request->get('deliveryType'));
        }

        if ($this->request->has('orderStatus')) {
            $query->where('order_status_id', $this->request->get('orderStatus'));
        }

        return $query->paginate(config('admin.show_items_per_page'));
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

        $order->load('orderStatus');
    }
}
