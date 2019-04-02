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
     * Determine whether the user can view the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        return $this->isUserSaleManager($user) || $order->users_id === $user->id;
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
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
        return $this->isUserSaleManager($user) || ($order->orderStatus->id === OrderStatusInterface::HANDLING && $order->users_id === $user->id);
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {

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
        return $this->isUserSaleManager($user) || ($order->users_id === $user->id && $order->orderStatus->id !== OrderStatusInterface::CANCELLED);
    }

    private function isUserSaleManager($user):bool
    {
        return (bool)$user->roles()->where('id', RoleInterface::USER_MANAGER)->count();
    }
}
