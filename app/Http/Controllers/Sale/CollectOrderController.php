<?php

namespace App\Http\Controllers\Sale;

use App\Contracts\Order\OrderStatusInterface;
use App\Models\Order;
use App\Support\Orders\OrderManagerBroker;
use App\Support\Orders\OrderStorage;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CollectOrderController extends Controller
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
     * @var OrderStorage
     */
    private $orderStorage;

    /**
     * CollectOrderController constructor.
     * @param Order $order
     * @param OrderManagerBroker $orderManagerBroker
     * @param OrderStorage $orderStorage
     */
    public function __construct(Order $order, OrderManagerBroker $orderManagerBroker, OrderStorage $orderStorage)
    {
        $this->orderManagerBroker = $orderManagerBroker;
        $this->order = $order;
        $this->orderStorage = $orderStorage;
    }

    /**
     * Collect order.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function collect(Request $request)
    {
        $orderId = $request->get('order_id');
        $order = $this->order->newQuery()->findOrFail($orderId);

        $this->authorize('collect', $order);

        // set order storage
        if (!$this->orderStorage->setOrderStorage($order)){
            // ToDo. redirect to form (manual select storage)
        }

        DB::beginTransaction();
        // set order status
        $this->changeOrderStatus($order);

        // close manager session
        $this->orderManagerBroker->endOrderManagerSession($order);
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
            'order_status_id' => OrderStatusInterface::COLLECTING,
        ]);
    }
}
