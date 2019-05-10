<?php

namespace App\Models;

use App\Models\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class VirtualCategory extends Model
{
    use Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'virtual_categories';

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attributeValue()
    {
        return $this->belongsTo('App\Models\AttributeValue', 'attribute_values_id', 'id');
    }

    /**
     * @param  string $value
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
     * @param  string $value
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
     * @param  string $value
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
     * @param  string $value
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
     * @param  string $value
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
     * @param  string $value
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
     * @param  string $value
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
     * @param  string $value
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
}
