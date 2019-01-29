<?php

namespace App\Models;

use App\Models\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;
use Mews\Purifier\Facades\Purifier;

class Category extends Model
{
    use NodeTrait;
    use Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'categories';

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
    public $translatable = ['name', 'title', 'description', 'keywords', 'content'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'category_product', 'categories_id', 'products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function filters()
    {
        return $this->belongsToMany('App\Models\Filter', 'category_filter', 'categories_id', 'filters_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categoryFilter()
    {
        return $this->hasMany('App\Models\CategoryFilter', 'categories_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendorCategories()
    {
        return $this->belongsToMany('App\Models\VendorCategory', 'vendor_local_categories', 'categories_id', 'vendor_categories_id', 'id')->withPivot('auto_add_new_products')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function virtualCategories()
    {
        return $this->hasMany('App\Models\VirtualCategory', 'categories_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function attributeValues()
    {
        return $this->hasManyThrough('App\Models\AttributeValue', 'virtual_categories', 'categories_id', 'attribute_values_id', 'id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function synchronizingProducts()
    {
        return$this->hasMany('App\Models\SynchronizingProduct', 'categories_id', 'id');
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setContentRuAttribute($value)
    {
        $this->attributes['content_ru'] = Purifier::clean($value);
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setContentUkAttribute($value)
    {
        $this->attributes['content_uk'] = Purifier::clean($value);
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
