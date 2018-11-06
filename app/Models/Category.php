<?php

namespace App\Models;

use App\Models\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'categories_id', 'id');
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendorCategory()
    {
        return $this->belongsTo('App\Models\VendorCategory', 'vendor_categories_id', 'id');
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
}
