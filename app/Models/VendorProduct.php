<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VendorProduct extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'vendor_products';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendorCategories()
    {
        return $this->belongsToMany('App\Models\VendorCategory', 'vendor_category_product', 'vendor_products_id', 'vendor_categories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorCategoryProducts()
    {
        return $this->hasMany('App\Models\VendorCategoryProduct', 'vendor_products_id', 'id');
    }

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
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendors_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorStockProducts()
    {
        return $this->hasMany('App\Models\VendorStockProduct', 'vendor_products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendorStocks()
    {
        return $this->belongsToMany('App\Models\VendorStock', 'vendor_stock_product', 'vendor_products_id', 'vendor_stocks_id');
    }

    /**
     * Transform timestamp to carbon.
     *
     * @param  string  $value
     * @return string
     */
    public function getVendorCreatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }
}
