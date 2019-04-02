<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * @return BelongsTo
     */
    public function deliveryType()
    {
        return $this->belongsTo('App\Models\DeliveryType', 'delivery_types_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function orderStatus()
    {
        return $this->belongsTo('App\Models\OrderStatus', 'order_status_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function orderAddress()
    {
        return $this->belongsTo('App\Models\OrderAddress', 'order_addresses_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function orderRecipient()
    {
        return $this->belongsTo('App\Models\OrderRecipient', 'order_recipients_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'order_product', 'orders_id', 'products_id')->withPivot('count', 'price');
    }

    /**
     * @return HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany('App\Models\OrderProduct', 'orders_id', 'id');
    }

    /**
     * Transform timestamp to carbon.
     *
     * @param  string $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }

    /**
     * Transform timestamp to carbon.
     *
     * @param  string $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }
}
