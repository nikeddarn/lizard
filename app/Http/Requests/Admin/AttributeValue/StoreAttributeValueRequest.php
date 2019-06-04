<?php

namespace App\Http\Requests\Admin\AttributeValue;

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
        $unique = Rule::unique('attribute_values')->where('attributes_id', request()->get('attributeId'));

        return [
            'attributeId' => 'integer',
            'value_ru' => ['required', 'string', 'max:128', $unique],
            'value_uk' => ['required', 'string', 'max:128', $unique],
            'url' => ['required', 'string', 'max:256', $unique],
            'image' => 'nullable|image',
        ];
    }
}
