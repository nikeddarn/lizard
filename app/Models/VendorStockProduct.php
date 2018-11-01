<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorStockProduct extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'vendor_stock_product';

    /**
     * Array of composite primary keys.
     *
     * @var array
     */
    protected $primaryKey = ['vendor_products_id', 'vendors_stocks_id'];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function storages()
    {
        return $this->hasMany('App\Models\Storage', 'cities_id', 'id');
    }
}
