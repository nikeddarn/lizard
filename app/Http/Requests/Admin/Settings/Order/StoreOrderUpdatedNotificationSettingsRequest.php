<?php
/**
 * Update notifications settings request.
 */

namespace App\Http\Requests\Admin\Settings\Order;


use App\Channels\SmsChannel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderUpdatedNotificationSettingsRequest extends FormRequest
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
                return $this->request->has('order_updated_user_channels') && in_array('mail', $this->request->get('order_updated_user_channels'));
            }),
        ];

        $userSmsChannelRules = [
            'nullable',
            'string',
            Rule::requiredIf(function () {
                return $this->request->has('order_updated_user_channels') && in_array(SmsChannel::class, $this->request->get('order_updated_user_channels'));
            }),
        ];

        $managerSmsChannelRules = [
            'nullable',
            'string',
            Rule::requiredIf(function () {
                return $this->request->has('order_updated_manager_channels') && in_array(SmsChannel::class, $this->request->get('order_updated_manager_channels'));
            }),
        ];

        return [
            'order_updated_user_channels.*' => 'nullable|string',

            'order_updated_mail_header_ru' => $userMailChannelRules,
            'order_updated_mail_header_uk' => $userMailChannelRules,
            'order_updated_mail_text_ru' => $userMailChannelRules,
            'order_updated_mail_text_uk' => $userMailChannelRules,

            'order_updated_sms_text_ru' => $userSmsChannelRules,
            'order_updated_sms_text_uk' => $userSmsChannelRules,

            'order_updated_manager_channels.*' => 'nullable|string',

            'order_updated_manager_text_ru' => $managerSmsChannelRules,
            'order_updated_manager_text_uk' => $managerSmsChannelRules,
        ];
    }
}
