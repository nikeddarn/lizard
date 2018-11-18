<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorAttribute extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'vendors_attributes';

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
    protected $primaryKey = ['vendors_id', 'attributes_id', 'vendor_attribute_id'];

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
    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute', 'attributes_id', 'id');
    }
}
