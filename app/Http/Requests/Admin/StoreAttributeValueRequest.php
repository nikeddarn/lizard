<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttributeValueRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attributeId' => 'numeric',
            'value_ru' => ['string', 'max:32', Rule::unique('attribute_values')],
            'value_ua' => ['string', 'max:32', Rule::unique('attribute_values')],
            'image' => 'nullable|image',
        ];
    }
}
