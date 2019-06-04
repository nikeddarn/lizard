<?php

namespace App\Models;

use App\Models\Support\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

class DealerCategory extends Model
{
    use NodeTrait;
    use Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'dealer_categories';

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
     * @return array
     */
    protected function getScopeAttributes()
    {
        return [ 'dealers_id' ];
    }

    /**
     * @return BelongsTo
     */
    public function dealer()
    {
        return $this->belongsTo('App\Models\Dealer', 'dealers_id', 'id');
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
