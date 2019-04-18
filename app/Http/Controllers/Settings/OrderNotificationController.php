<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Admin\Settings\Order\StoreOrderCreatedNotificationSettingsRequest;
use App\Http\Requests\Admin\Settings\Order\StoreOrderDeletedNotificationSettingsRequest;
use App\Http\Requests\Admin\Settings\Order\StoreOrderUpdatedNotificationSettingsRequest;
use App\Http\Requests\Admin\Settings\StoreOrderNotificationSettingsRequest;
use App\Support\Settings\SettingsRepository;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class OrderNotificationController extends Controller
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * OrderNotificationController constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @return View
     */
    public function edit()
    {
        $orderCreatedNotificationData = $this->settingsRepository->getProperty('notifications.order.created');
        $orderUpdatedNotificationData = $this->settingsRepository->getProperty('notifications.order.updated');
        $orderDeletedNotificationData = $this->settingsRepository->getProperty('notifications.order.deleted');

        return view('content.admin.settings.notifications.order.index')->with(compact('orderCreatedNotificationData', 'orderUpdatedNotificationData', 'orderDeletedNotificationData'));
    }

    /**
     * @param StoreOrderCreatedNotificationSettingsRequest $request
     * @return RedirectResponse
     */
    public function setOrderCreatedNotificationsData(StoreOrderCreatedNotificationSettingsRequest $request)
    {
        $orderCreatedNotificationData = [];

        if ($request->has('order_created_user_channels')){
            $orderCreatedNotificationData['user']['channels'] = $request->get('order_created_user_channels');
        }

        $orderCreatedNotificationData['user']['mail']['header']['ru'] = $request->get('order_created_mail_header_ru');
        $orderCreatedNotificationData['user']['mail']['header']['uk'] = $request->get('order_created_mail_header_uk');
        $orderCreatedNotificationData['user']['mail']['text']['ru'] = $request->get('order_created_mail_text_ru');
        $orderCreatedNotificationData['user']['mail']['text']['uk'] = $request->get('order_created_mail_text_uk');

        $orderCreatedNotificationData['user']['sms']['text']['ru'] = $request->get('order_created_sms_text_ru');
        $orderCreatedNotificationData['user']['sms']['text']['uk'] = $request->get('order_created_sms_text_uk');

        if ($request->has('order_created_manager_channels')){
            $orderCreatedNotificationData['manager']['channels'] = $request->get('order_created_manager_channels');
        }

        $orderCreatedNotificationData['manager']['text']['ru'] = $request->get('order_created_manager_text_ru');
        $orderCreatedNotificationData['manager']['text']['uk'] = $request->get('order_created_manager_text_uk');

        $this->settingsRepository->setProperty('notifications.order.created', $orderCreatedNotificationData);

        return redirect(route('admin.notifications.order.edit'))->with([
            'successful' => true,
        ]);
    }

    /**
     * @param StoreOrderUpdatedNotificationSettingsRequest $request
     * @return RedirectResponse
     */
    public function setOrderUpdatedNotificationsData(StoreOrderUpdatedNotificationSettingsRequest $request)
    {
        $orderUpdatedNotificationData = [];

        if ($request->has('order_updated_user_channels')){
            $orderUpdatedNotificationData['user']['channels'] = $request->get('order_updated_user_channels');
        }

        $orderUpdatedNotificationData['user']['mail']['header']['ru'] = $request->get('order_updated_mail_header_ru');
        $orderUpdatedNotificationData['user']['mail']['header']['uk'] = $request->get('order_updated_mail_header_uk');
        $orderUpdatedNotificationData['user']['mail']['text']['ru'] = $request->get('order_updated_mail_text_ru');
        $orderUpdatedNotificationData['user']['mail']['text']['uk'] = $request->get('order_updated_mail_text_uk');

        $orderUpdatedNotificationData['user']['sms']['text']['ru'] = $request->get('order_updated_sms_text_ru');
        $orderUpdatedNotificationData['user']['sms']['text']['uk'] = $request->get('order_updated_sms_text_uk');

        if ($request->has('order_updated_manager_channels')){
            $orderUpdatedNotificationData['manager']['channels'] = $request->get('order_updated_manager_channels');
        }

        $orderUpdatedNotificationData['manager']['text']['ru'] = $request->get('order_updated_manager_text_ru');
        $orderUpdatedNotificationData['manager']['text']['uk'] = $request->get('order_updated_manager_text_uk');

        $this->settingsRepository->setProperty('notifications.order.updated', $orderUpdatedNotificationData);

        return redirect(route('admin.notifications.order.edit'))->with([
            'successful' => true,
        ]);
    }

    /**
     * @param StoreOrderDeletedNotificationSettingsRequest $request
     * @return RedirectResponse
     */
    public function setOrderDeletedNotificationsData(StoreOrderDeletedNotificationSettingsRequest $request)
    {
        $orderDeletedNotificationData = [];

        if ($request->has('order_deleted_user_channels')){
            $orderDeletedNotificationData['user']['channels'] = $request->get('order_deleted_user_channels');
        }

        $orderDeletedNotificationData['user']['mail']['header']['ru'] = $request->get('order_deleted_mail_header_ru');
        $orderDeletedNotificationData['user']['mail']['header']['uk'] = $request->get('order_deleted_mail_header_uk');
        $orderDeletedNotificationData['user']['mail']['text']['ru'] = $request->get('order_deleted_mail_text_ru');
        $orderDeletedNotificationData['user']['mail']['text']['uk'] = $request->get('order_deleted_mail_text_uk');

        $orderDeletedNotificationData['user']['sms']['text']['ru'] = $request->get('order_deleted_sms_text_ru');
        $orderDeletedNotificationData['user']['sms']['text']['uk'] = $request->get('order_deleted_sms_text_uk');

        if ($request->has('order_deleted_manager_channels')){
            $orderDeletedNotificationData['manager']['channels'] = $request->get('order_deleted_manager_channels');
        }

        $orderDeletedNotificationData['manager']['text']['ru'] = $request->get('order_deleted_manager_text_ru');
        $orderDeletedNotificationData['manager']['text']['uk'] = $request->get('order_deleted_manager_text_uk');

        $this->settingsRepository->setProperty('notifications.order.deleted', $orderDeletedNotificationData);

        return redirect(route('admin.notifications.order.edit'))->with([
            'successful' => true,
        ]);
    }
}
