<?php

namespace App\Policies\Admin;

use App\Contracts\Auth\RoleInterface;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $currentUser
     * @param  \App\Models\User $accessingUser
     * @return mixed
     */
    public function view(User $currentUser, User $accessingUser)
    {
        // only for admins
        return $currentUser->hasAnyRole([
            RoleInterface::ADMIN,
        ]);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $currentUser
     * @param  \App\Models\User $accessingUser
     * @return mixed
     */
    public function modify(User $currentUser, User $accessingUser)
    {
        // only for admins (not self)
        return $currentUser->hasAnyRole([
            RoleInterface::ADMIN,
        ]) && $currentUser->id !== $accessingUser->id;
    }
}
