<?php

namespace App\Notifications\Order;

use App\Messages\SmsMessage;
use App\Models\Order;
use App\Support\Settings\SettingsRepository;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderDeletedUserNotification extends Notification
{
    use Queueable;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var array
     */
    private $settings;

    /**
     * Create a new notification instance.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;

        $settingsRepository = app()->make(SettingsRepository::class);
        $this->settings = $settingsRepository->getProperty('notifications.order.deleted');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return isset($this->settings['user']['channels']) ? $this->settings['user']['channels'] : [];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $locale = app()->getLocale();
        $mailMessageData = $this->settings['user']['mail'];

        $headerTextTemplate = $mailMessageData['header'][$locale];
        $headerText = $this->replaceNotificationPlaceholders($headerTextTemplate, $notifiable);

        $bodyTextTemplate = $mailMessageData['text'][$locale];
        $bodyText = $this->replaceNotificationPlaceholders($bodyTextTemplate, $notifiable);

        return (new MailMessage)
            ->subject(trans('shop.order.deleted.subject'))
            ->markdown('mail.order.order_deleted', [
                'headerText' => $headerText,
                'bodyText' => $bodyText,
            ]);
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed $notifiable
     * @return SmsMessage
     * @throws Exception
     */
    public function toSms($notifiable)
    {
        $locale = app()->getLocale();
        $smsMessageData = $this->settings['user']['sms'];

        $smsTextTemplate = $smsMessageData['text'][$locale];
        $smsText = $this->replaceNotificationPlaceholders($smsTextTemplate, $notifiable);

        $recipientPhone = $this->order->orderRecipient->phone;

        return (new SmsMessage())
            ->setText($smsText)
            ->setRecipient($recipientPhone);
    }

    /**
     * Replace placeholders with notification data.
     *
     * @param string $textTemplate
     * @param $notifiable
     * @return string
     */
    private function replaceNotificationPlaceholders(string $textTemplate, $notifiable):string
    {
        $replacementPatterns = [
            '/USER_NAME/',
            '/ORDER_ID/',
            '/ORDER_TOTAL_SUM/',
            '/CREATED_AT/',
            '/UPDATED_AT/',
        ];

        $replacementValues = [
            $notifiable->name,
            $this->order->id,
            $this->order->total_sum,
            $this->order->created_at,
            $this->order->updated_at,
        ];

        return preg_replace($replacementPatterns, $replacementValues, $textTemplate);
    }
}
