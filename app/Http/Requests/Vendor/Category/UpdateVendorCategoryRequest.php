<?php

namespace App\Http\Requests\Vendor\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorCategoryRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vendorCategoriesId' => ['required', 'integer'],
            'min_profit_sum_to_download_product' => 'nullable|numeric',
            'min_profit_percents_to_download_product' => 'nullable|numeric',
            'min_profit_sum_to_publish_product' => 'nullable|numeric',
            'min_profit_percents_to_publish_product' => 'nullable|numeric',
            'download_product_max_age' => 'nullable|numeric',
        ];
    }
}
