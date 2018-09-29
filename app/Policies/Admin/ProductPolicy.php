<?php

namespace App\Policies\Admin;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return $user->hasAnyRole(config('policies.' . Product::class));
    }

    /**
     * Determine whether the user can view the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function view(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can create products.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function update(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function delete(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can restore the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function restore(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function forceDelete(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the category.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function uploadImage(User $user, Product $product)
    {
        return false;
    }
}
