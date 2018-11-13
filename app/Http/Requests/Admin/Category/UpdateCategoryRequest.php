<?php

namespace App\Http\Requests\Admin\Category;

use App\Rules\ParentCategoryHasNotProducts;
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
            'name_ru' => ['required', 'string', 'max:64', $uniqueRule],
            'name_ua' => ['required', 'string', 'max:64', $uniqueRule],
            'url' => ['required', 'string', 'max:64', $uniqueRule],
            'parent_id' => ['required', 'numeric', new ParentCategoryHasNotProducts()],
            'title_ru' => ['nullable', 'string', 'max:128', $uniqueRule],
            'title_ua' => ['nullable', 'string', 'max:128', $uniqueRule],
            'description_ru' => ['nullable', 'string', 'max:255', $uniqueRule],
            'description_ua' => ['nullable', 'string', 'max:255', $uniqueRule],
            'keywords_ru' => 'nullable|string|max:128',
            'keywords_ua' => 'nullable|string|max:128',
            'content_ru' => 'nullable|string',
            'content_ua' => 'nullable|string',
            'image' => 'nullable|image',
        ];
    }
}
