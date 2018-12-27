<?php

namespace App\Models;

use App\Contracts\Vendor\SyncTypeInterface;
use App\Models\Support\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'vendors';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['balance'];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorStock()
    {
        return $this->hasMany('App\Models\VendorStock', 'vendors_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorCategories()
    {
        return $this->hasMany('App\Models\VendorCategory', 'vendors_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorProducts()
    {
        return $this->hasMany('App\Models\VendorProduct', 'vendors_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorAttribute()
    {
        return $this->hasMany('App\Models\VendorAttribute', 'vendors_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorAttributeValue()
    {
        return $this->hasMany('App\Models\VendorAttributeValue', 'vendors_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function synchronizingProducts()
    {
        return $this->hasMany('App\Models\SynchronizingProduct', 'vendors_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function insertingProducts()
    {
        return $this->hasMany('App\Models\SynchronizingProduct', 'vendors_id', 'id')
            ->where('sync_type', SyncTypeInterface::INSERT_PRODUCT);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function updatingProducts()
    {
        return $this->hasMany('App\Models\SynchronizingProduct', 'vendors_id', 'id')
            ->where('sync_type', SyncTypeInterface::UPDATE_PRODUCT)->groupBy('vendor_product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function brands()
    {
        return $this->belongsToMany('App\Models\Brand', 'vendor_brands', 'vendors_id', 'brands_id');
    }

    /**
     * Transform timestamp to carbon.
     *
     * @param  string  $value
     * @return string
     */
    public function getSyncPricesAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }

    /**
     * Transform timestamp to carbon.
     *
     * @param  string  $value
     * @return string
     */
    public function getSyncNewProductsAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }
}
