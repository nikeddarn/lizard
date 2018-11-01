<?php

namespace App\Http\Requests\Admin;

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
        return [
            'value_ru' => ['string', 'max:32', Rule::unique('attribute_values')->ignore(request('id'), 'id')],
            'value_ua' => ['string', 'max:32', Rule::unique('attribute_values')->ignore(request('id'), 'id')],
            'url' => ['string', 'max:64', Rule::unique('attribute_values')->ignore(request('id'), 'id')],
            'image' => 'nullable|image',
        ];
    }
}
