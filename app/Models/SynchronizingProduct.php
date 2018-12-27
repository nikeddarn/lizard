<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SynchronizingProduct extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'synchronizing_products';

    /**
     * Array of composite primary keys.
     *
     * @var array
     */
    protected $primaryKey = ['jobs_id'];

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
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'categories_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function vendorCategory()
    {
        return $this->belongsTo('App\Models\VendorCategory', 'vendor_categories_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendors_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function job()
    {
        return $this->belongsTo('App\Models\Job', 'jobs_id', 'jobs_id');
    }
}
