<?php
/**
 * SMS sender interface.
 */

namespace App\Contracts\Channels;


use App\Messages\SmsMessage;

interface SmsChannelSenderInterface
{
    /**
     * Send an sms.
     *
     * @param SmsMessage $message
     *
     * @return bool
     */
    public function send(SmsMessage $message);

    /**
     * Get sms sender balance.
     *
     * @return float
     */
    public function getBalance():float;
}
