<?php

namespace App\Policies\Admin;

use App\Models\Attribute;
use App\Models\User;
use App\Models\AttributeValue;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttributeValuePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return $user->hasAnyRole(config('policies.' . Attribute::class));
    }

    /**
     * Determine whether the user can view the attribute value.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return mixed
     */
    public function view(User $user, AttributeValue $attributeValue)
    {
        //
    }

    /**
     * Determine whether the user can create attribute values.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the attribute value.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return mixed
     */
    public function update(User $user, AttributeValue $attributeValue)
    {
        //
    }

    /**
     * Determine whether the user can delete the attribute value.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return mixed
     */
    public function delete(User $user, AttributeValue $attributeValue)
    {
        //
    }

    /**
     * Determine whether the user can restore the attribute value.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return mixed
     */
    public function restore(User $user, AttributeValue $attributeValue)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the attribute value.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return mixed
     */
    public function forceDelete(User $user, AttributeValue $attributeValue)
    {
        //
    }
}
