<?php

namespace App\Http\Requests\Shop;

use App\Contracts\Order\DeliveryTypesInterface;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderDeliveryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->session()->put('order_id', request()->get('order_id'));

        return [
            'order_id' => 'required|integer',
            'delivery_type' => 'required|integer',
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => ['nullable', 'required_unless:delivery_type,' . DeliveryTypesInterface::SELF, 'string', 'max:512'],
            'city_id' => ['nullable', 'required_if:delivery_type,' . DeliveryTypesInterface::COURIER, 'integer'],
        ];
    }
}
