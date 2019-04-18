<?php

namespace App\Listeners\Orders;

use App\Notifications\Order\OrderDeletedManagerNotification;
use App\Notifications\Order\OrderDeletedUserNotification;
use App\Support\Orders\OrderManagerBroker;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendOrderDeletedNotifications
{
    /**
     * @var OrderManagerBroker
     */
    private $orderManagerBroker;

    /**
     * SendOrderCreatedNotifications constructor.
     * @param OrderManagerBroker $orderManagerBroker
     */
    public function __construct(OrderManagerBroker $orderManagerBroker)
    {
        $this->orderManagerBroker = $orderManagerBroker;
    }

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

        // define order manager
        $orderManager = $this->orderManagerBroker->getCurrentOrFreeOrderManager($order);

        try {
            DB::beginTransaction();
            //set manager for given order
            $this->orderManagerBroker->setOrderManagerNotified($order, $orderManager);

            // notify user's manager
            $orderManager->notify(new OrderDeletedManagerNotification($order));
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            Log::info('Can\'t notify user manager ' . $orderManager->name . '<br/>' . $exception->getMessage());
        }
    }
}
