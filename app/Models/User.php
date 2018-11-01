<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_role', 'users_id', 'roles_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userRoles()
    {
        return $this->hasMany('App\Models\UserRole', 'users_id', 'id');
    }

    /**
     * Has user any role or role from given array?
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles = null)
    {
        if ($roles) {
            return (bool)$this->roles()->whereIn('id', $roles)->count();
        } else {
            return (bool)$this->roles()->count();
        }
    }

    /**
     * Has user any role or role from given array?
     *
     * @return bool
     */
    public function isEmployee()
    {
        return (bool)$this->roles()->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favouriteProducts()
    {
        return $this->belongsToMany('App\Models\Product', 'favourite_products', 'users_id', 'products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recentProducts()
    {
        return $this->belongsToMany('App\Models\Product', 'recent_products', 'users_id', 'products_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function timeLimitedRecentProducts()
    {
        return $this->belongsToMany('App\Models\Product', 'recent_products', 'users_id', 'products_id')->wherePivot('updated_at', '>=', Carbon::now()->subDays(config('shop.recent_product_ttl')));
    }
}
