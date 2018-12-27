<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCategoryProduct extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'vendor_category_product';

    /**
     * Array of composite primary keys.
     *
     * @var array
     */
    protected $primaryKey = ['vendor_categories_id', 'vendor_products_id'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendorCategory()
    {
        return $this->belongsTo('App\Models\VendorCategory', 'vendor_categories_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendorProduct()
    {
        return $this->belongsTo('App\Models\VendorProduct', 'vendor_products_id', 'id');
    }
}
