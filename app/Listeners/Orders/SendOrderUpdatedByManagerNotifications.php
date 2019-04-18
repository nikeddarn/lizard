<?php

namespace App\Listeners\Orders;

use App\Notifications\Order\OrderUpdatedUserNotification;
use Exception;

class SendOrderUpdatedByManagerNotifications
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     * @throws Exception
     */
    public function handle($event)
    {
        $order = $event->order;
        $user = $order->user;

        // notify user
        $user->notify(new OrderUpdatedUserNotification($order));
    }
}
