<?php

namespace App\Http\Requests\Vendor\Category;

use App\Rules\LeafCategory;
use Illuminate\Foundation\Http\FormRequest;

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
            'categories_id' => ['required', 'integer', new LeafCategory()],
        ];
    }
}
