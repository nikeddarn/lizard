<?php
/**
 * Update SEO settings request.
 */

namespace App\Http\Requests\Admin\Settings;


use Illuminate\Foundation\Http\FormRequest;

class UpdateShopSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'get_exchange_rate' => 'required|string',
            'update_rate_time' => 'required|integer',
            'usd_rate' => 'nullable|numeric',
            'min_user_price_group' => 'required|integer',
            'show_products_per_page' => 'required|integer',
            'show_product_comments_per_page' => 'required|integer',
            'recent_product_ttl' => 'required|integer',
            'show_product_rate_from_review_counts' => 'required|integer',
            'show_product_defect_rate_from_sold_counts' => 'required|integer',
            'new_product_badge_ttl' => 'required|integer',
            'price_down_badge_ttl' => 'required|integer',
            'ending_badge_products_count' => 'required|integer',
        ];
    }
}
