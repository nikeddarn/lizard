<?php

namespace App\Http\Requests\Content\ProductGroup;

use Illuminate\Foundation\Http\FormRequest;

class SortGroupsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group_id.*' => 'required|integer',
        ];
    }
}
