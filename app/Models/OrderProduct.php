<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'order_product';

    /**
     * Array of composite primary keys.
     *
     * @var array
     */
    protected $primaryKey = ['orders_id', 'products_id'];

    /**
     * Non auto incrementing primary key.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'orders_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'products_id', 'id');
    }
}
