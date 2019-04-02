<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderRecipient extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'order_recipients';

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
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'order_recipients_id', 'id');
    }
}
