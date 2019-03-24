<?php

namespace App\Models;

use App\Models\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Storage extends Model
{
    use Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'storages';

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
    public function storageProducts()
    {
        return $this->hasMany('App\Models\StorageProduct', 'storages_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function storagePhones()
    {
        return $this->hasMany('App\Models\StoragePhone', 'storages_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City', 'cities_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function workDays()
    {
        return $this->belongsToMany('App\Models\WorkDay', 'storage_work_day', 'storages_id', 'work_days_id')->withPivot('start_time', 'end_time');
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
