<?php

namespace App\Http\Requests\Vendor\Category;

use Illuminate\Foundation\Http\FormRequest;

class CreateVendorCategoryRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vendors_id' => ['required', 'integer'],
            'vendor_own_category_id' => ['required', 'integer'],
            'min_profit_sum_to_download_product' => 'nullable|numeric',
            'min_profit_percents_to_download_product' => 'nullable|numeric',
            'min_profit_sum_to_publish_product' => 'nullable|numeric',
            'min_profit_percents_to_publish_product' => 'nullable|numeric',
            'download_product_max_age' => 'nullable|numeric',
        ];
    }
}
