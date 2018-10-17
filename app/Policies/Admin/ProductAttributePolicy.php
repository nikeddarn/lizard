<?php

namespace App\Policies\Admin;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductAttribute;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductAttributePolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        return $user->hasAnyRole(config('policies.' . Product::class));
    }

    /**
     * Determine whether the user can view the product attribute.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductAttribute  $productAttribute
     * @return mixed
     */
    public function view(User $user, ProductAttribute $productAttribute)
    {
        //
    }

    /**
     * Determine whether the user can create product attributes.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the product attribute.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductAttribute  $productAttribute
     * @return mixed
     */
    public function update(User $user, ProductAttribute $productAttribute)
    {
        //
    }

    /**
     * Determine whether the user can delete the product attribute.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductAttribute  $productAttribute
     * @return mixed
     */
    public function delete(User $user, ProductAttribute $productAttribute)
    {
        //
    }

    /**
     * Determine whether the user can restore the product attribute.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductAttribute  $productAttribute
     * @return mixed
     */
    public function restore(User $user, ProductAttribute $productAttribute)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the product attribute.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductAttribute  $productAttribute
     * @return mixed
     */
    public function forceDelete(User $user, ProductAttribute $productAttribute)
    {
        //
    }
}
