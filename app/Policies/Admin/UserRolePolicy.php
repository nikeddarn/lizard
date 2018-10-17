<?php

namespace App\Policies\Admin;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserRolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRole  $userRole
     * @return mixed
     */
    public function view(User $user, UserRole $userRole)
    {
        return $user->hasAnyRole(config('policies.' . User::class . 'admin'));
    }

    /**
     * Determine whether the user can create user roles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(config('policies.' . User::class . 'admin'));
    }

    /**
     * Determine whether the user can update the user role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRole  $userRole
     * @return mixed
     */
    public function update(User $user, UserRole $userRole)
    {
        return $user->hasAnyRole(config('policies.' . User::class . 'admin'));
    }

    /**
     * Determine whether the user can delete the user role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRole  $userRole
     * @return mixed
     */
    public function delete(User $user, UserRole $userRole)
    {
        // disallow self user operation
        if ($user->id === $userRole->users_id) {
            return false;
        }

        return $user->hasAnyRole(config('policies.' . User::class . 'admin'));
    }

    /**
     * Determine whether the user can restore the user role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRole  $userRole
     * @return mixed
     */
    public function restore(User $user, UserRole $userRole)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the user role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRole  $userRole
     * @return mixed
     */
    public function forceDelete(User $user, UserRole $userRole)
    {
        //
    }
}
