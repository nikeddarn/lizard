<?php

namespace App\Models;

use App\Models\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttributeValue extends Model
{
    use Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'attribute_values';

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
    public $translatable = ['value'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute', 'attributes_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productAttributes()
    {
        return $this->hasMany('App\Models\ProductAttribute', 'attribute_values_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorAttributeValues()
    {
        return $this->hasMany('App\Models\VendorAttributeValue', 'attribute_values_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vendors()
    {
        return $this->belongsToMany('App\Models\Vendor', 'vendor_attribute_values', 'attribute_values_id', 'vendors_id');
    }

    /**
     * Set attribute's name_ru.
     *
     * @param  string  $value
     * @return void
     */
    public function setValueRuAttribute($value)
    {
        $this->attributes['value_ru'] = Str::ucfirst(Str::lower($value));
    }

    /**
     * Set attribute's name_ua.
     *
     * @param  string  $value
     * @return void
     */
    public function setValueUaAttribute($value)
    {
        $this->attributes['value_ua'] = Str::ucfirst(Str::lower($value));
    }

    /**
     * Set attribute's name_ua.
     *
     * @param  string  $value
     * @return void
     */
    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = Str::lower($value);
    }
}
