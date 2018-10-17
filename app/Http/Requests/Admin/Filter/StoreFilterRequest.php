<?php

namespace App\Http\Requests\Admin\Filter;

use Illuminate\Foundation\Http\FormRequest;

class StoreFilterRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_ru' => 'required|max:32',
            'name_ua' => 'required|max:32',
        ];
    }
}
