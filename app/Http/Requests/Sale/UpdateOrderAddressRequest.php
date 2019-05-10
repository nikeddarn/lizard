<?php

namespace App\Http\Requests\Sale;

use App\Contracts\Order\DeliveryTypesInterface;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_id' => 'required|integer',
            'delivery_type' => 'required|integer',
            'address' => ['nullable', 'required_unless:delivery_type,' . DeliveryTypesInterface::SELF, 'string', 'max:512'],
            'city_id' => ['nullable', 'required_if:delivery_type,' . DeliveryTypesInterface::COURIER, 'integer'],
            'storage_id' => ['nullable', 'required_if:delivery_type,' . DeliveryTypesInterface::SELF, 'integer'],
        ];
    }
}
