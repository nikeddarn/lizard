<?php
/**
 * Update SEO settings request.
 */

namespace App\Http\Requests\Admin\Settings;


use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'min_profit_sum_to_offer_product' => 'required|numeric',
            'min_profit_sum_to_price_discount' => 'required|numeric',
            'min_profit_percents_to_price_discount' => 'required|numeric',
            'column_discounts*' => 'required|numeric',
        ];
    }
}
