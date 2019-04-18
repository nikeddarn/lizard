<?php

namespace App\Listeners\Orders;

use App\Notifications\Order\OrderDeletedUserNotification;
use Exception;

class SendOrderDeletedByManagerNotifications
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
        $user->notify(new OrderDeletedUserNotification($order));
    }
}
