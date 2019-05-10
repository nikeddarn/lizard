<?php

namespace App\Http\Requests\Admin\ProductVideo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreProductVideoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'video_youtube' => 'nullable|string|max:255',
            'video_mp4' => 'nullable|file',
            'video_webm' => 'nullable|file',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // check video
            if ((request()->get('video_youtube') && request()->has('video_mp4')) || (request()->get('video_youtube') && request()->has('video_webm'))) {
                $validator->errors()->add('video_youtube', trans('validation.multiply_product_video_source'));
            }
        });
    }
}
