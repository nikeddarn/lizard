<?php

namespace App\Models;

use App\Models\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
{
    use Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'cities';

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
    public function storages()
    {
        return $this->hasMany('App\Models\Storage', 'cities_id', 'id');
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
}
