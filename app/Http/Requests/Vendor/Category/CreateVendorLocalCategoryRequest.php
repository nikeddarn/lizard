<?php

namespace App\Http\Requests\Vendor\Category;

use App\Rules\LeafCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateVendorLocalCategoryRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vendor_category_id' => ['required', 'integer'],
            'categories_id' => ['required', 'integer', new LeafCategory(), Rule::unique('vendor_local_categories')->where(function ($query) {
                $query->where([
                    ['vendor_categories_id', '=', request()->get('vendor_category_id')],
                    ['categories_id', '=', request()->get('categories_id')],
                ]);
            })],
        ];
    }
}
