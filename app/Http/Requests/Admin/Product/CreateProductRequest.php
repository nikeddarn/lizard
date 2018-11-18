<?php

namespace App\Http\Requests\Admin\Product;

use App\Models\Attribute;
use App\Rules\LeafCategory;
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
            'model_ru' => ['nullable', 'string', 'max:64', Rule::unique('products')],
            'model_ua' => ['nullable', 'string', 'max:64', Rule::unique('products')],
            'articul' => 'nullable|string',
            'code' => 'nullable|string',
            'categories_id.*' => ['required', 'numeric', 'distinct', new LeafCategory()],
            'title_ru' => ['nullable', 'string', 'max:128', Rule::unique('products')],
            'title_ua' => ['nullable', 'string', 'max:128', Rule::unique('products')],
            'description_ru' => ['nullable', 'string', 'max:255', Rule::unique('products')],
            'description_ua' => ['nullable', 'string', 'max:255', Rule::unique('products')],
            'keywords_ru' => 'nullable|string|max:128',
            'keywords_ua' => 'nullable|string|max:128',
            'content_ru' => 'nullable|string',
            'content_ua' => 'nullable|string',
            'min_order_quantity' => 'nullable|integer',
            'price1' => 'nullable|numeric',
            'price2' => 'nullable|numeric',
            'price3' => 'nullable|numeric',
            'image.*' => 'nullable|image',
            'attribute_id.*' => 'nullable|integer',
            'attribute_values_id.*' => 'nullable|integer|distinct',
            'filter_id.*' => 'nullable|integer',
            'is_new' => 'integer',
            'warranty' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'volume' => 'nullable|numeric',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach (array_count_values(request()->get('attribute_id')) as $attributeId => $attributeCount) {

                if ($attributeCount === 1) {
                    continue;
                }

                $attribute = Attribute::where('id', $attributeId)->first();

                if (!$attribute->multiply_product_values) {
                    $validator->errors()->add('attribute_id', trans('validation.multiply_product_values', ['attribute' => $attribute->name]));
                }
            }
        });
    }
}
