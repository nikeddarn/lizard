<?php

namespace App\Http\Requests\Admin;

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
            'name_ru' => ['required', 'string', 'max:64', $this->createUniqueRule()],
            'name_ua' => ['required', 'string', 'max:64', $this->createUniqueRule()],
            'value_ru.*' => ['required', 'string', 'max:32', 'distinct', Rule::unique('attribute_values', 'value_ru')],
            'value_ua.*' => ['required', 'string', 'max:32', 'distinct', Rule::unique('attribute_values', 'value_ua')],
            'url.*' => ['required', 'string', 'max:64', 'distinct', Rule::unique('attribute_values', 'url')],
            'image.*' => ['nullable', 'image'],
        ];
    }

    /**
     * Create unique rule by categories table ignoring current category (on update).
     *
     * @return $this|\Illuminate\Validation\Rules\Unique
     */
    private function createUniqueRule()
    {
        if (request()->has('id')){
            return Rule::unique('attributes')->ignore(request('id'), 'id');
        }else{
            return Rule::unique('attributes');
        }
    }
}
