<?php
/**
 * Update notifications settings request.
 */

namespace App\Http\Requests\Admin\Settings\Order;


use App\Channels\SmsChannel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderCreatedNotificationSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userMailChannelRules = [
            'nullable',
            'string',
            Rule::requiredIf(function () {
                return $this->request->has('order_created_user_channels') && in_array('mail', $this->request->get('order_created_user_channels'));
            }),
        ];

        $userSmsChannelRules = [
            'nullable',
            'string',
            Rule::requiredIf(function () {
                return $this->request->has('order_created_user_channels') && in_array(SmsChannel::class, $this->request->get('order_created_user_channels'));
            }),
        ];

        $managerSmsChannelRules = [
            'nullable',
            'string',
            Rule::requiredIf(function () {
                return $this->request->has('order_created_manager_channels') && in_array(SmsChannel::class, $this->request->get('order_created_manager_channels'));
            }),
        ];

        return [
            'order_created_user_channels.*' => 'nullable|string',

            'order_created_mail_header_ru' => $userMailChannelRules,
            'order_created_mail_header_uk' => $userMailChannelRules,
            'order_created_mail_text_ru' => $userMailChannelRules,
            'order_created_mail_text_uk' => $userMailChannelRules,

            'order_created_sms_text_ru' => $userSmsChannelRules,
            'order_created_sms_text_uk' => $userSmsChannelRules,

            'order_created_manager_channels.*' => 'nullable|string',

            'order_created_manager_text_ru' => $managerSmsChannelRules,
            'order_created_manager_text_uk' => $managerSmsChannelRules,
        ];
    }
}
