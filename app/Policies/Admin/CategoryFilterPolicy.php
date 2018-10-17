<?php

namespace App\Policies\Admin;

use App\Models\Category;
use App\Models\User;
use App\Models\CategoryFilter;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryFilterPolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        return $user->hasAnyRole(config('policies.' . Category::class));
    }

    /**
     * Determine whether the user can view the category filter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CategoryFilter  $categoryFilter
     * @return mixed
     */
    public function view(User $user, CategoryFilter $categoryFilter)
    {
        //
    }

    /**
     * Determine whether the user can create category filters.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the category filter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CategoryFilter  $categoryFilter
     * @return mixed
     */
    public function update(User $user, CategoryFilter $categoryFilter)
    {
        //
    }

    /**
     * Determine whether the user can delete the category filter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CategoryFilter  $categoryFilter
     * @return mixed
     */
    public function delete(User $user, CategoryFilter $categoryFilter)
    {
        //
    }

    /**
     * Determine whether the user can restore the category filter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CategoryFilter  $categoryFilter
     * @return mixed
     */
    public function restore(User $user, CategoryFilter $categoryFilter)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the category filter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CategoryFilter  $categoryFilter
     * @return mixed
     */
    public function forceDelete(User $user, CategoryFilter $categoryFilter)
    {
        //
    }
}
