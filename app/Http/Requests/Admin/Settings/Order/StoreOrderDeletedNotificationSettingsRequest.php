<?php
/**
 * Update notifications settings request.
 */

namespace App\Http\Requests\Admin\Settings\Order;


use App\Channels\SmsChannel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderDeletedNotificationSettingsRequest extends FormRequest
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
                return $this->request->has('order_deleted_user_channels') && in_array('mail', $this->request->get('order_deleted_user_channels'));
            }),
        ];

        $userSmsChannelRules = [
            'nullable',
            'string',
            Rule::requiredIf(function () {
                return $this->request->has('order_deleted_user_channels') && in_array(SmsChannel::class, $this->request->get('order_deleted_user_channels'));
            }),
        ];

        $managerSmsChannelRules = [
            'nullable',
            'string',
            Rule::requiredIf(function () {
                return $this->request->has('order_deleted_manager_channels') && in_array(SmsChannel::class, $this->request->get('order_deleted_manager_channels'));
            }),
        ];

        return [
            'order_deleted_user_channels.*' => 'nullable|string',

            'order_deleted_mail_header_ru' => $userMailChannelRules,
            'order_deleted_mail_header_uk' => $userMailChannelRules,
            'order_deleted_mail_text_ru' => $userMailChannelRules,
            'order_deleted_mail_text_uk' => $userMailChannelRules,

            'order_deleted_sms_text_ru' => $userSmsChannelRules,
            'order_deleted_sms_text_uk' => $userSmsChannelRules,

            'order_deleted_manager_channels.*' => 'nullable|string',

            'order_deleted_manager_text_ru' => $managerSmsChannelRules,
            'order_deleted_manager_text_uk' => $managerSmsChannelRules,
        ];
    }
}
