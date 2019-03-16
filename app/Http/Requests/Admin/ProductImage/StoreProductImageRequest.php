<?php
/**
 * Store product image request.
 */

namespace App\Http\Requests\Admin\ProductImage;


use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required|image',
        ];
    }
}
