<?php

namespace App\Http\Controllers\Sale;

use App\Models\DeliveryType;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
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
     * OrderController constructor.
     * @param Request $request
     * @param Order $order
     * @param OrderStatus $orderStatus
     * @param DeliveryType $deliveryType
     */
    public function __construct(Request $request, Order $order, OrderStatus $orderStatus, DeliveryType $deliveryType)
    {
        $this->order = $order;
        $this->orderStatus = $orderStatus;
        $this->deliveryType = $deliveryType;
        $this->request = $request;
    }

    /**
     * Show orders.
     *
     * @return View
     */
    public function index()
    {
        if (!Gate::allows('shop-orders')) {
            return abort(403);
        }

        $orders = $this->getOrders();

        $orderStatusTypes = $this->orderStatus->newQuery()->get();
        $deliveryTypes = $this->deliveryType->newQuery()->get();

        return view('content.sale.orders.list.index')->with(compact('orders', 'orderStatusTypes', 'deliveryTypes'));
    }

    /**
     * Get filtered orders.
     *
     * @return LengthAwarePaginator
     */
    private function getOrders()
    {
        $query = $this->order->newQuery()->with('orderStatus', 'deliveryType');

        if ($this->request->has('createdAt')) {
            if ($this->request->get('createdAt') === 'asc') {
                $query->orderBy('created_at');
            } elseif ($this->request->get('createdAt') === 'desc') {
                $query->orderByDesc('created_at');
            }
        }else{
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
}
