<?php

namespace App\Http\Controllers\Sale;

use App\Http\Requests\Sale\UpdateOrderRecipientRequest;
use App\Models\Order;
use App\Models\OrderRecipient;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderRecipientController extends Controller
{
    /**
     * @var Order
     */
    private $order;
    /**
     * @var OrderRecipient
     */
    private $orderRecipient;

    /**
     * OrderRecipientController constructor.
     * @param Order $order
     * @param OrderRecipient $orderRecipient
     */
    public function __construct(Order $order, OrderRecipient $orderRecipient)
    {
        $this->order = $order;
        $this->orderRecipient = $orderRecipient;
    }

    /**
     * Edit order product.
     *
     * @param int $orderId
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(int $orderId)
    {
        $order = $this->order->newQuery()->with('orderRecipient')->findOrFail($orderId);

        $this->authorize('updateDelivery', $order);

        return view('content.sale.orders.edit.recipient.index')->with(compact('order'));
    }

    /**
     * @param UpdateOrderRecipientRequest $request
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateOrderRecipientRequest $request)
    {
        $orderId = $request->get('order_id');
        $order = $this->order->newQuery()->with('orderRecipient')->findOrFail($orderId);

        $this->authorize('updateDelivery', $order);

        $name = $request->get('name');
        $phone = $request->get('phone');


        $order->orderRecipient->update(compact('name', 'phone'));

        return redirect(route('admin.order.show', ['order_id' => $orderId]));
    }
}
