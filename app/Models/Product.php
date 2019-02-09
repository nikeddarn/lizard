<?php

namespace App\Models;

use App\Contracts\Shop\StorageDepartmentsInterface;
use App\Events\Shop\ProductDeleted;
use App\Events\Shop\ProductSaved;
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
    public $translatable = ['name', 'title', 'description', 'keywords', 'content', 'manufacturer', 'brief_content', 'model'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => ProductSaved::class,
        'deleted' => ProductDeleted::class,
    ];

    /**
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'category_product', 'products_id', 'categories_id');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availableStorageProducts()
    {
        return $this->hasMany('App\Models\StorageProduct', 'products_id', 'id')
            ->where('storage_departments_id', '=', StorageDepartmentsInterface::STOCK)
            ->where('available_quantity', '>', 0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expectingStorageProducts()
    {
        return $this->hasMany('App\Models\StorageProduct', 'products_id', 'id')
            ->where('storage_departments_id', '=', StorageDepartmentsInterface::STOCK)
            ->whereNotNull('available_time');
    }

    /**
     * @return BelongsToMany
     */
    public function storages()
    {
        return $this->belongsToMany('App\Models\Storage', 'storage_products', 'products_id', 'storages_id');
    }

    /**
     * @return BelongsToMany
     */
    public function availableProductStorages()
    {
        return $this->belongsToMany('App\Models\Storage', 'storage_products', 'products_id', 'storages_id')
            ->where('storage_departments_id', '=', StorageDepartmentsInterface::STOCK)
            ->wherePivot('available_quantity', '>', 0);
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
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough|\Illuminate\Database\Query\Builder
     */
    public function availableVendorProducts()
    {
        return $this->hasMany('App\Models\VendorProduct', 'products_id', 'id')
            ->where('available', '>', 0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough|\Illuminate\Database\Query\Builder
     */
    public function expectingVendorProducts()
    {
        return $this->hasMany('App\Models\VendorProduct', 'products_id', 'id')
            ->whereNotNull('available_time');
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendors()
    {
        return $this->belongsToMany('App\Models\Vendor', 'vendor_products', 'products_id', 'vendors_id');
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
    public function setBriefContentUkAttribute($value)
    {
        $this->attributes['brief_content_uk'] = Purifier::clean($value);
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
    public function setContentUkAttribute($value)
    {
        $this->attributes['content_uk'] = Purifier::clean($value);
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
    public function setManufacturerUkAttribute($value)
    {
        $this->attributes['manufacturer_uk'] = Str::ucfirst($value);
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setNameRuAttribute($value)
    {
        $this->attributes['name_ru'] = Str::ucfirst($value);
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setNameUkAttribute($value)
    {
        $this->attributes['name_uk'] = Str::ucfirst($value);
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setTitleRuAttribute($value)
    {
        $this->attributes['title_ru'] = Str::ucfirst($value);
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setTitleUkAttribute($value)
    {
        $this->attributes['title_uk'] = Str::ucfirst($value);
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setDescriptionRuAttribute($value)
    {
        $this->attributes['description_ru'] = Str::ucfirst($value);
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setDescriptionUkAttribute($value)
    {
        $this->attributes['description_uk'] = Str::ucfirst($value);
    }

    /**
     * Set attribute's name_uk.
     *
     * @param  string  $value
     * @return void
     */
    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = Str::lower($value);
    }
}
