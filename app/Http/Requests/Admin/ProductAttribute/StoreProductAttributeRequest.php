<?php
/**
 * Store product attribute request.
 */

namespace App\Http\Requests\Admin\ProductAttribute;


use App\Models\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductAttributeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // get product id
        $productsId = $this->request->get('products_id');

        return [
            'attributes_id' => 'integer',
            'attribute_values_id' => ['integer', Rule::unique('product_attribute', 'attribute_values_id')->where('products_id', $productsId)],
            'products_id' => 'integer',
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
        // get product id
        $productsId = $this->request->get('products_id');

        $validator->after(function ($validator) use ($productsId) {
            $validator->sometimes('attributes_id', Rule::unique('product_attribute', 'attributes_id')->where('products_id', $productsId), function ($input) {
                return !Attribute::query()->where('id', $input->attributes_id)->first()->multiply_product_values;
            });
        });
    }
}
