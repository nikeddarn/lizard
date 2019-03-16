<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMainPageSeoDataRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'title_ru' => 'required|string',
            'title_uk' => 'required|string',
            'description_ru' => 'required|string',
            'description_uk' => 'required|string',
            'keywords_ru' => 'nullable|string',
            'keywords_uk' => 'nullable|string',
        ];
    }
}
