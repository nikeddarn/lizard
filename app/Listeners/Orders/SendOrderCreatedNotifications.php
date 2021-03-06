<?php

namespace App\Listeners\Orders;

use App\Notifications\Order\OrderCreatedManagerNotification;
use App\Notifications\Order\OrderCreatedUserNotification;
use App\Support\Orders\OrderManagerBroker;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendOrderCreatedNotifications
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
     * @param object $event
     * @return void
     * @throws Exception
     */
    public function handle($event)
    {
        $order = $event->order;
        $user = $order->user;

        // notify user
        $user->notify(new OrderCreatedUserNotification($order));

        // define order manager
        $orderManager = $this->orderManagerBroker->getCurrentOrFreeOrderManager($order);

        if ($orderManager) {
            try {
                DB::beginTransaction();
                //set manager for given order
                $this->orderManagerBroker->setOrderManagerNotified($order, $orderManager);

                // notify user's manager
                $orderManager->notify(new OrderCreatedManagerNotification($order));
                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();
                Log::info('Can\'t notify user manager ' . $orderManager->name . '<br/>' . $exception->getMessage());
            }
        }else{
            Log::info('Can\'t notify user manager. No one manager present');
        }
    }
}
