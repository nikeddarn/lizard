<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoragePhone extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'storage_phones';

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
}
