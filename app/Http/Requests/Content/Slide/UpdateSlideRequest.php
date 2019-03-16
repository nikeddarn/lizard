<?php

namespace App\Http\Requests\Content\Slide;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'slide_id' => 'required|integer',
            'name_ru' => 'nullable|string',
            'name_uk' => 'nullable|string',
            'text_ru' => 'nullable|string',
            'text_uk' => 'nullable|string',
            'url_ru' => 'nullable|string',
            'url_uk' => 'nullable|string',
        ];
    }
}
