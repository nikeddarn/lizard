<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_ru' => ['required', 'string', 'max:64', Rule::unique('products')->ignore(request('id'), 'id')],
            'name_uk' => ['required', 'string', 'max:64', Rule::unique('products')->ignore(request('id'), 'id')],
            'model_ru' => ['nullable', 'string', 'max:64', Rule::unique('products')->ignore(request('id'), 'id')],
            'model_uk' => ['nullable', 'string', 'max:64', Rule::unique('products')->ignore(request('id'), 'id')],
            'url' => ['required', 'string', 'max:128', Rule::unique('products')->ignore(request('id'), 'id')],
            'categories_id.*' => 'required|numeric',
            'title_ru' => ['nullable', 'string', 'max:128', Rule::unique('products')->ignore(request('id'), 'id')],
            'title_uk' => ['nullable', 'string', 'max:128', Rule::unique('products')->ignore(request('id'), 'id')],
            'description_ru' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore(request('id'), 'id')],
            'description_uk' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore(request('id'), 'id')],
            'keywords_ru' => 'nullable|string|max:128',
            'keywords_uk' => 'nullable|string|max:128',
            'content_ru' => 'nullable|string',
            'content_uk' => 'nullable|string',
            'min_order_quantity' => 'nullable|integer',
            'price1' => 'nullable|numeric',
            'price2' => 'nullable|numeric',
            'price3' => 'nullable|numeric',
            'is_new' => 'integer',
            'warranty' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'volume' => 'nullable|numeric',
        ];
    }
}
