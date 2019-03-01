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
            'column_discounts*' => 'required|numeric',
        ];
    }
}
