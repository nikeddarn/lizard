<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Support\Translatable;

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeValues()
    {
        return $this->hasMany('App\Models\AttributeValue', 'attributes_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function productAttributes()
    {
        return $this->hasManyThrough('App\Models\ProductAttribute', 'App\Models\AttributeValue', 'attributes_id', 'attribute_values_id');
    }
}
