<?php

namespace App\Http\Requests\Admin\AttributeValue;

use Illuminate\Foundation\Http\FormRequest;

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
            'attributeId' => 'integer',
            'value_ru' => ['string', 'max:128', 'unique'],
            'value_uk' => ['string', 'max:128', 'unique'],
            'url' => ['string', 'max:256', 'unique'],
            'image' => 'nullable|image',
        ];
    }
}
