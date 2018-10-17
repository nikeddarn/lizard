<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryFilter extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'category_filter';

    /**
     * Array of composite primary keys.
     *
     * @var array
     */
    protected $primaryKey = ['categories_id', 'filters_id'];

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
    public function filter()
    {
        return $this->belongsTo('App\Models\Filter', 'filters_id', 'id');
    }
}
