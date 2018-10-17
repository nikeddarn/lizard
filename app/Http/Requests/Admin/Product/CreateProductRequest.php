<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_ru' => ['required', 'string', 'max:64', Rule::unique('products')],
            'name_ua' => ['required', 'string', 'max:64', Rule::unique('products')],
            'url' => ['required', 'string', 'max:128', Rule::unique('products')],
            'categories_id' => 'required|numeric',
            'title_ru' => ['required', 'string', 'max:128', Rule::unique('products')],
            'title_ua' => ['required', 'string', 'max:128', Rule::unique('products')],
            'description_ru' => ['required', 'string', 'max:255', Rule::unique('products')],
            'description_ua' => ['required', 'string', 'max:255', Rule::unique('products')],
            'keywords_ru' => 'nullable|string|max:128',
            'keywords_ua' => 'nullable|string|max:128',
            'content_ru' => 'nullable|string',
            'content_ua' => 'nullable|string',
            'price1' => 'nullable|numeric',
            'price2' => 'nullable|numeric',
            'price3' => 'nullable|numeric',
            'image.*' => 'nullable|image',
            'attribute_values_id.*' => 'nullable|numeric',
            'filter_id.*' => 'nullable|integer',
        ];
    }
}
