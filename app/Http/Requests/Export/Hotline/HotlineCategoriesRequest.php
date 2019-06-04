<?php


namespace App\Http\Requests\Export\Hotline;


use Illuminate\Foundation\Http\FormRequest;

class HotlineCategoriesRequest  extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'categories' => 'required|file',
        ];
    }
}
