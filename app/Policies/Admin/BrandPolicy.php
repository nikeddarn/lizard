<?php

namespace App\Policies\Admin;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return $user->hasAnyRole(config('policies.' . Brand::class));
    }

    /**
     * Determine whether the user can view the attribute.
     *
     * @param  \App\Models\User $user
     * @param Brand $brand
     * @return mixed
     */
    public function view(User $user, Brand $brand)
    {
        //
    }

    /**
     * Determine whether the user can create attributes.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the attribute.
     *
     * @param  \App\Models\User $user
     * @param Brand $brand
     * @return mixed
     */
    public function update(User $user, Brand $brand)
    {
        //
    }

    /**
     * Determine whether the user can delete the attribute.
     *
     * @param  \App\Models\User $user
     * @param Brand $brand
     * @return mixed
     */
    public function delete(User $user, Brand $brand)
    {
        //
    }

    /**
     * Determine whether the user can restore the attribute.
     *
     * @param  \App\Models\User $user
     * @param Brand $brand
     * @return mixed
     */
    public function restore(User $user, Brand $brand)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the attribute.
     *
     * @param  \App\Models\User $user
     * @param Brand $brand
     * @return mixed
     */
    public function forceDelete(User $user, Brand $brand)
    {
        //
    }
}
