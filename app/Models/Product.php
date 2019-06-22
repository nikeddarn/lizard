<?php

namespace App\Models;

use App\Contracts\Shop\StorageDepartmentsInterface;
use App\Events\Shop\ProductDeleted;
use App\Events\Shop\ProductDeleting;
use App\Events\Shop\ProductSaved;
use App\Models\Support\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Mews\Purifier\Facades\Purifier;

class Product extends Model
{
    use Translatable;
    use Searchable;

    // tnt search option
    public $asYouType = true;

    /**
     * Get the value used to index the model.
     *
     * @return mixed
     */
    public function getScoutKey()
    {
        return $this->id;
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            $this->getKeyName() => $this->getKey(),
            'name_ru' => $this->name_ru,
            'name_uk' => $this->name_uk,
            'model_ru' => $this->model_ru,
            'model_uk' => $this->model_uk,
            'articul' => $this->articul,
            'code' => $this->code,
            'brief_content_ru' => $this->brief_content_ru,
            'brief_content_uk' => $this->brief_content_uk,
            'content_ru' => $this->content_ru,
            'content_uk' => $this->content_uk,
            'manufacturer_ru' => $this->manufacturer_ru,
            'manufacturer_uk' => $this->manufacturer_uk,
        ];
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toExactMatchSearchableArray()
    {
        return [
            'articul' => $this->articul,
            'code' => $this->code,
            'model_ru' => $this->model_ru,
            'model_uk' => $this->model_uk,
            'name_ru' => $this->name_ru,
            'name_uk' => $this->name_uk,
        ];
    }

    /**
     * Is model searchable ?
     *
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        return (bool)$this->published;
    }

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
        'deleting' => ProductDeleting::class,
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productVideos()
    {
        return $this->hasMany('App\Models\ProductVideo', 'products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productFiles()
    {
        return $this->hasMany('App\Models\ProductFile', 'products_id', 'id');
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
    public function stockStorageProducts()
    {
        return $this->hasMany('App\Models\StorageProduct', 'products_id', 'id')
            ->where('storage_departments_id', '=', StorageDepartmentsInterface::STOCK);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availableOrExpectingStorageProducts()
    {
        return $this->hasMany('App\Models\StorageProduct', 'products_id', 'id')
            ->where('storage_departments_id', '=', StorageDepartmentsInterface::STOCK)
            ->where(function ($query) {
                $query->where('available_quantity', '>', 0)
                    ->orWhereNotNull('available_time');
            });
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
    public function stockStorages()
    {
        return $this->belongsToMany('App\Models\Storage', 'storage_products', 'products_id', 'storages_id')
            ->wherePivot('storage_departments_id', '=', StorageDepartmentsInterface::STOCK);
    }

    /**
     * @return BelongsToMany
     */
    public function availableProductStorages()
    {
        return $this->belongsToMany('App\Models\Storage', 'storage_products', 'products_id', 'storages_id')
            ->wherePivot('storage_departments_id', '=', StorageDepartmentsInterface::STOCK)
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
                $query->whereDate('expired', '>', Carbon::now())
                    ->orWhereNull('expired');
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
    public function availableOrExpectingVendorProducts()
    {
        return $this->hasMany('App\Models\VendorProduct', 'products_id', 'id')
            ->where(function ($query) {
                $query->where('available', '>', 0)
                    ->orWhereNotNull('available_time');
            });
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dealerProducts()
    {
        return $this->hasMany('App\Models\DealerProduct', 'products_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dealerProduct()
    {
        return $this->hasOne('App\Models\DealerProduct', 'products_id', 'id');
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dealers()
    {
        return $this->belongsToMany('App\Models\Dealer', 'dealer_product', 'products_id', 'dealers_id');
    }

    /**
     * @param string $value
     * @return void
     */
    public function setBriefContentRuAttribute($value)
    {
        if ($value) {
            $this->attributes['brief_content_ru'] = Purifier::clean($value);
        } else {
            $this->attributes['brief_content_ru'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setBriefContentUkAttribute($value)
    {
        if ($value) {
            $this->attributes['brief_content_uk'] = Purifier::clean($value);
        } else {
            $this->attributes['brief_content_uk'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setContentRuAttribute($value)
    {
        if ($value) {
            $this->attributes['content_ru'] = Purifier::clean($value);
        } else {
            $this->attributes['content_ru'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setContentUkAttribute($value)
    {
        if ($value) {
            $this->attributes['content_uk'] = Purifier::clean($value);
        } else {
            $this->attributes['content_uk'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setManufacturerRuAttribute($value)
    {
        if ($value) {
            $this->attributes['manufacturer_ru'] = Str::ucfirst($value);
        } else {
            $this->attributes['manufacturer_ru'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setManufacturerUkAttribute($value)
    {
        if ($value) {
            $this->attributes['manufacturer_uk'] = Str::ucfirst($value);
        } else {
            $this->attributes['manufacturer_uk'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setNameRuAttribute($value)
    {
        if ($value) {
            $this->attributes['name_ru'] = Str::ucfirst($value);
        } else {
            $this->attributes['name_ru'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setNameUkAttribute($value)
    {
        if ($value) {
            $this->attributes['name_uk'] = Str::ucfirst($value);
        } else {
            $this->attributes['name_uk'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setTitleRuAttribute($value)
    {
        if ($value) {
            $this->attributes['title_ru'] = Str::ucfirst($value);
        } else {
            $this->attributes['title_ru'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setTitleUkAttribute($value)
    {
        if ($value) {
            $this->attributes['title_uk'] = Str::ucfirst($value);
        } else {
            $this->attributes['title_uk'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setDescriptionRuAttribute($value)
    {
        if ($value) {
            $this->attributes['description_ru'] = Str::ucfirst($value);
        } else {
            $this->attributes['description_ru'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setDescriptionUkAttribute($value)
    {
        if ($value) {
            $this->attributes['description_uk'] = Str::ucfirst($value);
        } else {
            $this->attributes['description_uk'] = $value;
        }
    }

    /**
     * @param string $value
     * @return void
     */
    public function setUrlAttribute($value)
    {
        if ($value) {
            $this->attributes['url'] = Str::lower($value);
        } else {
            $this->attributes['url'] = $value;
        }
    }

    /**
     * Transform timestamp to carbon.
     *
     * @param string $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }

    /**
     * Transform timestamp to carbon.
     *
     * @param string $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }
}
