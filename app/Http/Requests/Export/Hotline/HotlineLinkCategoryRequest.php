<?php


namespace App\Http\Requests\Export\Hotline;


use Illuminate\Foundation\Http\FormRequest;

class HotlineLinkCategoryRequest  extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'category_id' => 'required|integer',
            'dealer_category_id' => 'required|integer',
        ];
    }
}
