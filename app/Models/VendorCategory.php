<?php

namespace App\Models;

use App\Models\Support\Translatable;
use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    use Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'vendor_categories';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * The attributes that should be selected depends on locale from JSON type field.
     *
     * @var array
     */
    public $translatable = ['name'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category','vendor_local_categories', 'vendor_categories_id', 'categories_id')->withPivot('auto_add_new_products')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function autoAddNewProductsCategories()
    {
        return $this->belongsToMany('App\Models\Category','vendor_local_categories', 'vendor_categories_id', 'categories_id')->wherePivot('auto_add_new_products', '=', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendorProducts()
    {
        return $this->belongsToMany('App\Models\VendorProduct', 'vendor_category_product', 'vendor_categories_id', 'vendor_products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorLocalCategories()
    {
        return $this->hasMany('App\Models\VendorLocalCategory', 'vendor_categories_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function synchronizingProducts()
    {
        return$this->hasMany('App\Models\SynchronizingProduct', 'vendor_categories_id', 'id');
    }
}
