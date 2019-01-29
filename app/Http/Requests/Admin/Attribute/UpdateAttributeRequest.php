<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $uniqueRule = Rule::unique('attributes')->ignore(request('id'), 'id');

        return [
            'name_ru' => ['required', 'string', 'max:128', $uniqueRule],
            'name_uk' => ['required', 'string', 'max:128', $uniqueRule],
        ];
    }
}
