<?php

namespace App\Http\Requests\Admin\Category\Real;

use App\Rules\EmptyCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $uniqueRule = Rule::unique('categories')->ignore(request()->route('id'), 'id');

        return [
            'url' => ['required', 'string', 'max:64', $uniqueRule],
            'parent_id' => ['required', 'numeric', new EmptyCategory()],
            'name_ru' => ['required', 'string', 'max:128', $uniqueRule],
            'name_uk' => ['required', 'string', 'max:128', $uniqueRule],
            'title_ru' => ['nullable', 'string', 'max:256', $uniqueRule],
            'title_uk' => ['nullable', 'string', 'max:256', $uniqueRule],
            'description_ru' => ['nullable', 'string', 'max:512', $uniqueRule],
            'description_uk' => ['nullable', 'string', 'max:512', $uniqueRule],
            'keywords_ru' => 'nullable|string|max:512',
            'keywords_uk' => 'nullable|string|max:512',
            'content_ru' => 'nullable|string',
            'content_uk' => 'nullable|string',
            'image' => 'image',
        ];
    }
}
