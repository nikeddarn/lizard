<?php
/**
 * Store product category request.
 */

namespace App\Http\Requests\Admin\ProductCategory;


use App\Rules\LeafCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductCategoryRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'categories_id' => [
                'required',
                'integer',
                Rule::unique('category_product', 'categories_id')->where('products_id', request()->get('products_id')),
                new LeafCategory(),
            ],
            'products_id' => 'required|integer',
        ];
    }
}
