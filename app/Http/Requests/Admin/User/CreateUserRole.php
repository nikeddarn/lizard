<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRole extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'users_id' => 'required|integer',
            'roles_id' => ['required', 'integer', Rule::unique('user_role', 'roles_id')->where('users_id', request()->get('users_id'))],
        ];
    }
}
