<?php

namespace App\Http\Controllers\Sale;

use App\Contracts\Order\OrderStatusInterface;
use App\Models\Order;
use App\Support\Orders\OrderManagerBroker;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CommitOrderController extends Controller
{
    /**
     * @var OrderManagerBroker
     */
    private $orderManagerBroker;
    /**
     * @var Order
     */
    private $order;

    /**
     * CollectOrderController constructor.
     * @param Order $order
     * @param OrderManagerBroker $orderManagerBroker
     */
    public function __construct(Order $order, OrderManagerBroker $orderManagerBroker)
    {
        $this->orderManagerBroker = $orderManagerBroker;
        $this->order = $order;
    }

    /**
     * Collect order.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function commit(Request $request)
    {
        $orderId = $request->get('order_id');
        $order = $this->order->newQuery()->findOrFail($orderId);

        $this->authorize('commit', $order);

        $manager = auth('web')->user();

        DB::beginTransaction();
        // set order status
        $this->changeOrderStatus($order);

        // close manager session
        $this->orderManagerBroker->commitOrder($order, $manager);

        // update user statistics

        // update user price column
        DB::commit();

        return redirect(route('admin.orders.index'));
    }

    /**
     * Change order's status.
     *
     * @param $order
     */
    private function changeOrderStatus($order)
    {
        $order->update([
            'order_status_id' => OrderStatusInterface::DELIVERED,
        ]);
    }
}
