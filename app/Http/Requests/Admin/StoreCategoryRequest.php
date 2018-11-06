<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_ru' => ['required', 'string', 'max:64', $this->createUniqueRule()],
            'name_ua' => ['required', 'string', 'max:64', $this->createUniqueRule()],
            'url' => ['required', 'string', 'max:64', $this->createUniqueRule()],
            'parent_id' => 'required|numeric',
            'title_ru' => ['nullable', 'string', 'max:128', $this->createUniqueRule()],
            'title_ua' => ['nullable', 'string', 'max:128', $this->createUniqueRule()],
            'description_ru' => ['nullable', 'string', 'max:255', $this->createUniqueRule()],
            'description_ua' => ['nullable', 'string', 'max:255', $this->createUniqueRule()],
            'keywords_ru' => 'nullable|string|max:128',
            'keywords_ua' => 'nullable|string|max:128',
            'content_ru' => 'nullable|string',
            'content_ua' => 'nullable|string',
            'image' => 'required|image',
            'filter_id.*' => 'nullable|integer',
        ];
    }

    /**
     * Create unique rule by categories table ignoring current category (on update).
     *
     * @return $this|\Illuminate\Validation\Rules\Unique
     */
    private function createUniqueRule()
    {
        if (request()->has('id')){
            return Rule::unique('categories')->ignore(request('id'), 'id');
        }else{
            return Rule::unique('categories');
        }
    }
}
