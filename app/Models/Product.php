<?php

namespace App\Models;

use App\Contracts\Shop\StorageDepartmentsInterface;
use App\Models\Support\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class Product extends Model
{
    use Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'products';

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
    public $translatable = ['name', 'title', 'description', 'keywords', 'content', 'manufacturer', 'brief_content'];

    /**
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'category_product', 'products_id', 'categories_id');
    }

    /**
     * @return BelongsToMany
     */
    public function vendorCategories()
    {
        return $this->belongsToMany('App\Models\VendorCategory', 'vendor_products', 'products_id', 'vendor_categories_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productImages()
    {
        return $this->hasMany('App\Models\ProductImage', 'products_id', 'id')->orderByDesc('priority');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function primaryImage()
    {
        return $this->hasOne('App\Models\ProductImage', 'products_id', 'id')->where('priority', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productAttributes()
    {
        return $this->hasMany('App\Models\ProductAttribute', 'products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->belongsToMany('App\Models\Attribute', 'product_attribute', 'products_id', 'attributes_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributeValues()
    {
        return $this->belongsToMany('App\Models\AttributeValue', 'product_attribute', 'products_id', 'attribute_values_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function filters()
    {
        return $this->belongsToMany('App\Models\Filter', 'product_filter', 'products_id', 'filters_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function storageProducts()
    {
        return $this->hasMany('App\Models\StorageProduct', 'products_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function stockStorages()
    {
        return $this->belongsToMany('App\Models\Storage', 'storage_products', 'products_id', 'storages_id')->wherePivot('storage_departments_id', '=', StorageDepartmentsInterface::STOCK)->withPivot('available_quantity', 'stock_quantity', 'available_time');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function badges()
    {
        return $this->belongsToMany('App\Models\Badge', 'product_badge', 'products_id', 'badges_id')->withPivot('expired');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function actualBadges()
    {
        return $this->belongsToMany('App\Models\Badge', 'product_badge', 'products_id', 'badges_id')
            ->withPivot('expired')
            ->where(function ($query) {
                $query->where('expired', '>', Carbon::now())->orWhereNull('expired');
            });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productComments()
    {
        return $this->hasMany('App\Models\ProductComment', 'products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recentProducts()
    {
        return $this->hasMany('App\Models\RecentProduct', 'products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favouriteProducts()
    {
        return $this->hasMany('App\Models\FavouriteProduct', 'products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorProducts()
    {
        return $this->hasMany('App\Models\VendorProduct', 'products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vendorProduct()
    {
        return $this->hasOne('App\Models\VendorProduct', 'products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categoryProducts()
    {
        return $this->hasMany('App\Models\CategoryProduct', 'products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brands_id', 'id');
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setBriefContentRuAttribute($value)
    {
        $this->attributes['brief_content_ru'] = Purifier::clean($value);
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setBriefContentUaAttribute($value)
    {
        $this->attributes['brief_content_ua'] = Purifier::clean($value);
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setContentRuAttribute($value)
    {
        $this->attributes['content_ru'] = Purifier::clean($value);
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setContentUaAttribute($value)
    {
        $this->attributes['content_ua'] = Purifier::clean($value);
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setManufacturerRuAttribute($value)
    {
        $this->attributes['manufacturer_ru'] = Str::ucfirst($value);
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setManufacturerUaAttribute($value)
    {
        $this->attributes['manufacturer_ua'] = Str::ucfirst($value);
    }
}
