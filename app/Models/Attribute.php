<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Support\Translatable;
use Illuminate\Support\Str;

class Attribute extends Model
{
    use Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'attributes';

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeValues()
    {
        return $this->hasMany('App\Models\AttributeValue', 'attributes_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productAttributes()
    {
        return $this->hasMany('App\Models\ProductAttribute', 'attributes_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorAttributes()
    {
        return $this->hasMany('App\Models\VendorAttribute', 'attributes_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_attribute', 'attributes_id', 'products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendors()
    {
        return $this->belongsToMany('App\Models\Vendor', 'vendor_attributes', 'attributes_id', 'vendors_id');
    }

    /**
     * Set attribute's name_ru.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameRuAttribute($value)
    {
        $this->attributes['name_ru'] = Str::ucfirst(Str::lower($value));
    }

    /**
     * Set attribute's name_uk.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameUkAttribute($value)
    {
        $this->attributes['name_uk'] = Str::ucfirst(Str::lower($value));
    }
}
