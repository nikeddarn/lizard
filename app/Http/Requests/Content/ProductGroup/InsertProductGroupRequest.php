<?php

namespace App\Http\Requests\Content\ProductGroup;

use Illuminate\Foundation\Http\FormRequest;

class InsertProductGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name_ru' => 'required|string',
            'name_uk' => 'required|string',
            'min_count' => 'required|integer',
            'max_count' => 'required|integer',
            'categories_id' => 'required|integer',
            'cast_product_method' => 'required|integer',
        ];
    }
}
