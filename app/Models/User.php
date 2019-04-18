<?php

namespace App\Models;

use App\Contracts\Order\OrderStatusInterface;
use App\Support\Settings\SettingsRepository;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
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
     * @return BelongsToMany
     */
    public function cartProducts()
    {
        return $this->belongsToMany('App\Models\Product', 'user_cart_product', 'users_id', 'products_id')->withPivot('count');
    }

    /**
     * @return HasMany
     */
    public function userCartProducts()
    {
        return $this->hasMany('App\Models\UserCartProduct', 'users_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_role', 'users_id', 'roles_id')->withTimestamps();
    }

    /**
     * @return HasMany
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
     * @return BelongsToMany
     */
    public function favouriteProducts()
    {
        return $this->belongsToMany('App\Models\Product', 'favourite_products', 'users_id', 'products_id');
    }

    /**
     * @return BelongsToMany
     */
    public function recentProducts()
    {
        return $this->belongsToMany('App\Models\Product', 'recent_products', 'users_id', 'products_id')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function timeLimitedRecentProducts()
    {
        $settingsRepository = app()->make(SettingsRepository::class);

        $recentProductTtl = $settingsRepository->getProperty('shop.recent_product_ttl');

        return $this->belongsToMany('App\Models\Product', 'recent_products', 'users_id', 'products_id')->wherePivot('updated_at', '>=', Carbon::now()->subDays($recentProductTtl));
    }

    /**
     * @return HasMany
     */
    public function orderAddresses()
    {
        return $this->hasMany('App\Models\OrderAddress', 'users_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function orderRecipients()
    {
        return $this->hasMany('App\Models\OrderRecipient', 'users_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'users_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function activeOrders()
    {
        return $this->hasMany('App\Models\Order', 'users_id', 'id')
            ->whereIn('order_status_id', [
                OrderStatusInterface::HANDLING,
                OrderStatusInterface::COLLECTING,
                OrderStatusInterface::COLLECTED,
                OrderStatusInterface::DELIVERING,
            ]);
    }

    /**
     * @return HasMany
     */
    public function orderManagers()
    {
        return $this->hasMany('App\Models\OrderManager', 'users_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function managerOrders()
    {
        return $this->belongsToMany('App\Models\Order', 'order_manager', 'users_id', 'orders_id')->withPivot('notified', 'took', 'completed');
    }

    /**
     * Transform timestamp to carbon.
     *
     * @param string $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }

    /**
     * Transform timestamp to carbon.
     *
     * @param string $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }

    /**
     * Route notifications for the sms channel.
     *
     * @return string
     */
    public function routeNotificationForSms()
    {
        return $this->phone;
    }
}
