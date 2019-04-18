<?php
/**
 * Sms channel.
 */

namespace App\Channels;


use App\Contracts\Channels\SmsChannelSenderInterface;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Throwable;

class SmsChannel
{
    /**
     * @var SmsChannelSenderInterface
     */
    private $smsChannelSender;

    /**
     * SmsChannel constructor.
     * @param SmsChannelSenderInterface $smsChannelSender
     */
    public function __construct(SmsChannelSenderInterface $smsChannelSender)
    {

        $this->smsChannelSender = $smsChannelSender;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);

        if (!$message->recipient) {
            $message->setRecipient($notifiable->routeNotificationForSms());
        }

        try {
            $this->smsChannelSender->send($message);
        } catch (Throwable $exception) {
            Log::info('Can\'t send sms message: ' . $exception->getMessage());
        }
    }
}
