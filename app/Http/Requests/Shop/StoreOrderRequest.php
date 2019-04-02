<?php

namespace App\Http\Requests\Shop;

use App\Contracts\Order\DeliveryTypesInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'delivery_type' => 'required|integer',
            'name' => 'required|string',
            'phone' => 'required|string',
            'login_email' => 'sometimes|nullable|required_without:register_email|email',
            'register_email' => 'sometimes|nullable|required_without:login_email|email',
            'address' => ['nullable', 'required_unless:delivery_type,' . DeliveryTypesInterface::SELF, 'string', 'max:512'],
            'city_id' => ['nullable', 'required_if:delivery_type,' . DeliveryTypesInterface::COURIER, 'integer'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->sometimes('register_password', 'required|confirmed|min:6', function ($input) {
            return $input->register_email && !($input->login_email && $input->login_password);
        })->sometimes('login_password', 'required|min:6', function ($input) {
            return $input->login_email && !($input->register_email && $input->register_password && $input->register_password_confirmation && $input->register_password === $input->register_password_confirmation);
        });

    }
}
