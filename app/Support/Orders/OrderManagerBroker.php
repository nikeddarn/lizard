<?php


namespace App\Support\Orders;


use App\Contracts\Auth\RoleInterface;
use App\Models\Order;
use App\Models\OrderManager;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderManagerBroker
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var OrderManager
     */
    private $orderManager;

    /**
     * OrderManager constructor.
     * @param User $user
     * @param OrderManager $orderManager
     */
    public function __construct(User $user, OrderManager $orderManager)
    {
        $this->user = $user;
        $this->orderManager = $orderManager;
    }

    /**
     * Cast user manager for given order.
     *
     * @param $order
     * @return User|Model|null
     */
    public function getCurrentOrFreeOrderManager($order)
    {
        // get manager that handling order now
        $orderManager = $this->getCurrentOrderManager($order);

        if (!$orderManager) {
            //get free manager
            $orderManager = $this->getFreeOrderManager();
        }

        return $orderManager;
    }

    /**
     * Set given manager as notified of this order.
     *
     * @param $order
     * @param $manager
     */
    public function setOrderManagerNotified($order, $manager)
    {
        $existingOrderManager = $this->retrieveOrderManagerModel($order, $manager);

        if ($existingOrderManager) {
            $existingOrderManager->update([
                'notified' => Carbon::now()->toDateTimeString(),
            ]);
        } else {
            $order->managers()->syncWithoutDetaching([
                $manager->id => [
                    'notified' => Carbon::now()->toDateTimeString(),
                ]
            ]);
        }
    }

    /**
     * Start session for given managers of this order.
     *
     * @param $order
     * @param $manager
     */
    public function beginOrderManagerSession($order, $manager)
    {
        $currentActiveOrderManager = $order->currentActiveOrderManager()->first();

        if ($currentActiveOrderManager) {
            if ($currentActiveOrderManager->users_id !== $manager->id) {

                DB::beginTransaction();

                // close prev manager session
                $currentActiveOrderManager->update([
                    'end_working' => Carbon::now()->toDateTimeString(),
                ]);

                //start new manager session
                $this->startNewManagerSession($order, $manager);

                DB::commit();
            }
        } else {
            //start new manager session
            $this->startNewManagerSession($order, $manager);
        }
    }

    /**
     * End active manager's session for given order.
     *
     * @param $order
     */
    public function endOrderManagerSession($order)
    {
        $order->currentActiveOrderManager()->update([
            'end_working' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * End active manager's session for given order.
     *
     * @param $order
     * @param $manager
     */
    public function commitOrder($order, $manager)
    {
        $this->retrieveOrderManagerModel($order, $manager)->update([
            'commit' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * Retrieve existing order manager.
     *
     * @param $order
     * @param $manager
     * @return OrderManager|Model|null
     */
    private function retrieveOrderManagerModel($order, $manager)
    {
        return $this->orderManager->newQuery()->where([
            ['users_id', '=', $manager->id],
            ['orders_id', '=', $order->id],
        ])->first();
    }

    /**
     * Start new session for manager.
     *
     * @param $order
     * @param $manager
     */
    private function startNewManagerSession($order, $manager)
    {
        $existingOrderManager = $this->retrieveOrderManagerModel($order, $manager);

        if ($existingOrderManager) {
            $existingOrderManager->update([
                'begin_working' => Carbon::now()->toDateTimeString(),
            ]);
        } else {
            $order->managers()->syncWithoutDetaching([
                $manager->id => [
                    'begin_working' => Carbon::now()->toDateTimeString(),
                ]
            ]);
        }
    }

    /**
     * Get manager that currently handling order.
     *
     * @param Order|Model $order
     * @return User|Model|null
     */
    private function getCurrentOrderManager($order)
    {
        $currentOrderManager = $order->currentOrderManager()->with('manager')->first();

        return $currentOrderManager ? $currentOrderManager->manager : null;
    }

    /**
     * Get free order manager.
     *
     * @return User|Model
     */
    private function getFreeOrderManager()
    {
        $userManager = $this->user->newQuery()
            ->whereNotNull('phone')
            ->whereHas('userRoles', function ($query) {
                $query->where('roles_id', RoleInterface::USER_MANAGER);
            })
            ->leftJoin('order_manager', 'order_manager.users_id', '=', 'users.id')
            ->groupBy('users.id')
            ->selectRaw('users.*, max(order_manager.notified) AS last_notified, IF(max(order_manager.end_working) < max(order_manager.begin_working), 1, 0) AS taken, IF(max(order_manager.end_working) < max(order_manager.notified), 1, 0) AS notified')
            ->orderByRaw('taken, notified, last_notified')
            ->first();

        if (!$userManager) {
            $userManager = $this->user->newQuery()
                ->whereNotNull('phone')
                ->whereHas('userRoles', function ($query) {
                    $query->where('roles_id', RoleInterface::ADMIN);
                })
                ->first();
        }

        return $userManager;
    }
}
