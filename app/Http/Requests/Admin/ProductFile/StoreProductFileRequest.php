<?php

namespace App\Http\Requests\Admin\ProductFile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductFileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_file_name_ru' => ['nullable', Rule::requiredIf(function (){
                return request()->get('product_file_name_uk') || request()->has('product_file');
            })],
            'product_file_name_uk' => ['nullable', Rule::requiredIf(function (){
                return request()->get('product_file_name_ru') || request()->has('product_file');
            })],
            'product_file' => ['nullable', 'file', Rule::requiredIf(function (){
                return request()->get('product_file_name_ru') || request()->get('product_file_name_uk');
            })],
        ];
    }
}
