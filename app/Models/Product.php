<?php

namespace App\Models;

use App\Models\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
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
    public $translatable = ['name', 'title', 'description', 'keywords', 'content'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'categories_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productImages()
    {
        return $this->hasMany('App\Models\ProductImage', 'products_id', 'id');
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
    public function attributeValues()
    {
        return $this->belongsToMany('App\Models\AttributeValue', 'product_attribute', 'products_id', 'attribute_values_id');
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
    public function setContentUaAttribute($value)
    {
        $this->attributes['content_ua'] = Purifier::clean($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function filters()
    {
        return $this->belongsToMany('App\Models\Filter', 'product_filter', 'products_id', 'filters_id');
    }
}
