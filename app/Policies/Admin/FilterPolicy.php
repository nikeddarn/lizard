<?php

namespace App\Policies\Admin;

use App\Models\User;
use App\Models\Filter;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilterPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return $user->hasAnyRole(config('policies.' . Filter::class));
    }

    /**
     * Determine whether the user can view the filter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Filter  $filter
     * @return mixed
     */
    public function view(User $user, Filter $filter)
    {
        //
    }

    /**
     * Determine whether the user can create filters.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the filter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Filter  $filter
     * @return mixed
     */
    public function update(User $user, Filter $filter)
    {
        //
    }

    /**
     * Determine whether the user can delete the filter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Filter  $filter
     * @return mixed
     */
    public function delete(User $user, Filter $filter)
    {
        //
    }

    /**
     * Determine whether the user can restore the filter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Filter  $filter
     * @return mixed
     */
    public function restore(User $user, Filter $filter)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the filter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Filter  $filter
     * @return mixed
     */
    public function forceDelete(User $user, Filter $filter)
    {
        //
    }
}
