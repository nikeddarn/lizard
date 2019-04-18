<?php

namespace App\Policies\Shop;

use App\Contracts\Auth\RoleInterface;
use App\Contracts\Order\OrderStatusInterface;
use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view orders list.
     *
     * @param User $user
     * @return mixed
     */
    public function viewList(User $user)
    {
        return $this->isUserOrderManager($user);
    }

    /**
     * Determine whether the user can view the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        if (!$this->isUserOwnerOrManager($user, $order)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        if (!$this->isUserOwnerOrManager($user, $order)) {
            return false;
        }

        return in_array($order->orderStatus->id, [
            OrderStatusInterface::HANDLING,
            OrderStatusInterface::COLLECTING,
            OrderStatusInterface::COLLECTED,
        ]);
    }

    /**
     * Determine whether the user can update order delivery.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function updateDelivery(User $user, Order $order)
    {
        if (!$this->isUserOwnerOrManager($user, $order)) {
            return false;
        }

        return in_array($order->orderStatus->id, [
            OrderStatusInterface::HANDLING,
            OrderStatusInterface::COLLECTING,
            OrderStatusInterface::COLLECTED,
            OrderStatusInterface::DELIVERING,
        ]);
    }

    /**
     * Determine whether the user can manage the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function manage(User $user, Order $order)
    {
        if (!$this->isUserOwnerOrManager($user, $order)) {
            return false;
        }

        $currentActiveOrderManager = $order->currentActiveOrderManager()->first();

        $checkOrderStatus = in_array($order->orderStatus->id, [
            OrderStatusInterface::HANDLING,
            OrderStatusInterface::COLLECTING,
            OrderStatusInterface::COLLECTED,
            OrderStatusInterface::DELIVERING,
        ]);

        return $checkOrderStatus && (!$currentActiveOrderManager || $currentActiveOrderManager->users_id === $user->id || $user->roles()->where('id', RoleInterface::ADMIN));
    }

    /**
     * Determine whether the manager can collect the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function collect(User $user, Order $order)
    {
        if (!$this->isUserOwnerOrManager($user, $order)) {
            return false;
        }

        return in_array($order->orderStatus->id, [
            OrderStatusInterface::HANDLING,
            OrderStatusInterface::COLLECTING,
            OrderStatusInterface::COLLECTED,
        ]);
    }

    /**
     * Determine whether the user can cancel the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function cancel(User $user, Order $order)
    {
        if (!$this->isUserOwnerOrManager($user, $order)) {
            return false;
        }

        return in_array($order->orderStatus->id, [
            OrderStatusInterface::HANDLING,
            OrderStatusInterface::COLLECTING,
            OrderStatusInterface::COLLECTED,
            OrderStatusInterface::DELIVERING,
        ]);
    }

    /**
     * Determine whether the user can update order delivery.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function commit(User $user, Order $order)
    {
        if (!$this->isUserOrderManager($user)) {
            return false;
        }

        return in_array($order->orderStatus->id, [
            OrderStatusInterface::COLLECTING,
            OrderStatusInterface::COLLECTED,
            OrderStatusInterface::DELIVERING,
        ]);
    }

    /**
     *
     *
     * @param User $user
     * @param Order $order
     * @return bool
     */
    private function isUserOwnerOrManager(User $user, Order $order): bool
    {
        return $order->users_id === $user->id || $this->isUserOrderManager($user);
    }

    /**
     * Is user admin or user manager ?
     *
     * @param User $user
     * @return bool
     */
    private function isUserOrderManager(User $user)
    {
        return (bool)$user->roles()
            ->whereIn('id', [
                RoleInterface::ADMIN,
                RoleInterface::USER_MANAGER,
            ])
            ->count();
    }
}
