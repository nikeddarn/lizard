<?php

namespace App\Http\Requests\Content\Slide;

use Illuminate\Foundation\Http\FormRequest;

class InsertSlideRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'slider_id' => 'required|integer',
            'image_ru' => 'required|image',
            'image_uk' => 'required|image',
            'name_ru' => 'nullable|string',
            'name_uk' => 'nullable|string',
            'text_ru' => 'nullable|string',
            'text_uk' => 'nullable|string',
            'url_ru' => 'nullable|string',
            'url_uk' => 'nullable|string',
        ];
    }
}
