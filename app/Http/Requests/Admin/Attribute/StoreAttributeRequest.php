<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttributeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_ru' => ['required', 'string', 'max:128', 'unique'],
            'name_uk' => ['required', 'string', 'max:128', 'unique'],
            'value_ru.*' => ['required', 'string', 'max:128', 'distinct', Rule::unique('attribute_values', 'value_ru')],
            'value_uk.*' => ['required', 'string', 'max:128', 'distinct', Rule::unique('attribute_values', 'value_uk')],
            'url.*' => ['required', 'string', 'max:256', 'distinct', Rule::unique('attribute_values', 'url')],
            'image.*' => ['nullable', 'image'],
        ];
    }
}
