<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'jobs';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function synchronizingProduct()
    {
        return $this->hasOne('App\Models\SynchronizingProduct', 'jobs_id', 'id');
    }
}
