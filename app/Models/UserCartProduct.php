<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCartProduct extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'user_cart_product';

    /**
     * Array of composite primary keys.
     *
     * @var array
     */
    protected $primaryKey = ['users_id', 'products_id'];

    /**
     * Non auto incrementing primary key.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'products_id', 'id');
    }
}
