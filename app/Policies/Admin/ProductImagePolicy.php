<?php

namespace App\Policies\Admin;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductImage;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductImagePolicy
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
     * Determine whether the user can view the product image.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductImage  $productImage
     * @return mixed
     */
    public function view(User $user, ProductImage $productImage)
    {
        //
    }

    /**
     * Determine whether the user can create product images.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the product image.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductImage  $productImage
     * @return mixed
     */
    public function update(User $user, ProductImage $productImage)
    {
        //
    }

    /**
     * Determine whether the user can delete the product image.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductImage  $productImage
     * @return mixed
     */
    public function delete(User $user, ProductImage $productImage)
    {
        //
    }

    /**
     * Determine whether the user can restore the product image.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductImage  $productImage
     * @return mixed
     */
    public function restore(User $user, ProductImage $productImage)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the product image.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductImage  $productImage
     * @return mixed
     */
    public function forceDelete(User $user, ProductImage $productImage)
    {
        //
    }
}
