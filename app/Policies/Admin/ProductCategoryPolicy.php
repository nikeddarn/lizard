<?php

namespace App\Policies\Admin;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductFilter;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductCategoryPolicy
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
     * Determine whether the user can view the product filter.
     *
     * @param  \App\Models\User $user
     * @param CategoryProduct $categoryProduct
     * @return mixed
     */
    public function view(User $user, CategoryProduct $categoryProduct)
    {
        //
    }

    /**
     * Determine whether the user can create product filters.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the product filter.
     *
     * @param  \App\Models\User $user
     * @param CategoryProduct $categoryProduct
     * @return mixed
     */
    public function update(User $user, CategoryProduct $categoryProduct)
    {
        //
    }

    /**
     * Determine whether the user can delete the product filter.
     *
     * @param  \App\Models\User $user
     * @param CategoryProduct $categoryProduct
     * @return mixed
     */
    public function delete(User $user, CategoryProduct $categoryProduct)
    {
        //
    }

    /**
     * Determine whether the user can restore the product filter.
     *
     * @param  \App\Models\User $user
     * @param CategoryProduct $categoryProduct
     * @return mixed
     */
    public function restore(User $user, CategoryProduct $categoryProduct)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the product filter.
     *
     * @param  \App\Models\User $user
     * @param CategoryProduct $categoryProduct
     * @return mixed
     */
    public function forceDelete(User $user, CategoryProduct $categoryProduct)
    {
        //
    }
}
