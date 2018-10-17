<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
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

    public function productAttributes()
    {
        return $this->hasMany('App\Models\ProductAttribute', 'attribute_values_id', 'id');
    }
}
