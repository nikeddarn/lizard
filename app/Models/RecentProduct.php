<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentProduct extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'recent_products';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Array of composite primary keys.
     *
     * @var array
     */
    protected $primaryKey = ['products_id', 'users_id'];

    /**
     * Non auto incrementing primary key.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id', 'id');
    }
}
