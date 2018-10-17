<?php

namespace App\Policies\Admin;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $accessingUser
     * @return mixed
     */
    public function view(User $user, User $accessingUser)
    {
        if ($accessingUser->isEmployee()) {
            return $user->hasAnyRole(config('policies.' . User::class . 'admin'));
        } else {
            return $user->hasAnyRole(config('policies.' . User::class . 'customer'));
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $accessingUser
     * @return mixed
     */
    public function update(User $user, User $accessingUser)
    {
        if ($accessingUser->isEmployee()) {
            return $user->hasAnyRole(config('policies.' . User::class . 'admin'));
        } else {
            return $user->hasAnyRole(config('policies.' . User::class . 'customer'));
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $accessingUser
     * @return mixed
     */
    public function delete(User $user, User $accessingUser)
    {
        // disallow delete self user
        if ($user->id === $accessingUser->id) {
            return false;
        }

        if ($accessingUser->isEmployee()) {
            return $user->hasAnyRole(config('policies.' . User::class . 'admin'));
        } else {
            return $user->hasAnyRole(config('policies.' . User::class . 'customer'));
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
