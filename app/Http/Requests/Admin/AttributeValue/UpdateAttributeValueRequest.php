<?php

namespace App\Http\Requests\Admin\AttributeValue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeValueRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $uniqueRile = Rule::unique('attribute_values')->ignore(request('id'), 'id');

        return [
            'value_ru' => ['required', 'string', 'max:128', $uniqueRile],
            'value_uk' => ['required', 'string', 'max:128', $uniqueRile],
            'url' => ['required', 'string', 'max:256', $uniqueRile],
            'image' => 'nullable|image',
        ];
    }
}
