<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageWorkDay extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'storage_work_day';

    /**
     * Array of composite primary keys.
     *
     * @var array
     */
    protected $primaryKey = ['storages_id', 'work_days_id'];

    /**
     * Non auto incrementing primary key.
     *
     * @var bool
     */
    public $incrementing = false;

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
    public function storage()
    {
        return $this->belongsTo('App\Models\Storage', 'storages_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workDay()
    {
        return $this->belongsTo('App\Models\WorkDay', 'work_days_id', 'id');
    }
}
