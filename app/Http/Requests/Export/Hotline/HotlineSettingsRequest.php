<?php


namespace App\Http\Requests\Export\Hotline;


use Illuminate\Foundation\Http\FormRequest;

class HotlineSettingsRequest  extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'firm_name' => 'required|string',
            'firm_id' => 'required|integer',
        ];
    }
}
