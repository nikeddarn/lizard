<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorBrand extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'vendor_brands';

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
    protected $primaryKey = ['vendors_id', 'brands_id', 'vendor_brand_id'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendors_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brands_id', 'id');
    }
}
