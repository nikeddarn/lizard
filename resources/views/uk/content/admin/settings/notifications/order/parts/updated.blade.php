<form method="post" action="{{ route('admin.notifications.order.updated.update') }}">
    @csrf

    <div class="card p-4 mb-5">

        <h5 class="mb-4">E-mail сообщение пользователю</h5>

        <div class="custom-control custom-checkbox mb-4">
            <input id="update-mail-user-channel" type="checkbox" name="order_updated_user_channels[]" value="mail"
                   class="custom-control-input multi-inputs-checkbox"{{ in_array('mail', old('order_updated_user_channels', [])) || (isset($orderUpdatedNotificationData['user']['channels']) && in_array('mail', $orderUpdatedNotificationData['user']['channels'])) ? ' checked' : '' }}>
            <label class="custom-control-label" for="update-mail-user-channel">Передавать по E-mail каналу</label>
        </div>

        <div class="row form-group">

            <div class="col-12 col-sm-6 mb-4">
                <label for="update-mail_header_ru">Заголовок сообщения (RU)</label>
                <input id="update-mail_header_ru" class="form-control" type="text" name="order_updated_mail_header_ru"
                       value="{{ old('order_updated_mail_header_ru', isset($orderUpdatedNotificationData['user']['mail']['header']['ru']) ? $orderUpdatedNotificationData['user']['mail']['header']['ru'] : '') }}">
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="update-mail_header_uk">Заголовок сообщения (UA)</label>
                <input id="update-mail_header_uk" class="form-control" type="text" name="order_updated_mail_header_uk"
                       value="{{ old('order_updated_mail_header_uk', isset($orderUpdatedNotificationData['user']['mail']['header']['uk']) ? $orderUpdatedNotificationData['user']['mail']['header']['uk'] : '') }}">
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="update-mail_text_ru">Текст сообщения (RU)</label>
                <textarea id="update-mail_text_ru" class="form-control" rows="4"
                          name="order_updated_mail_text_ru">{{ old('order_updated_mail_text_ru', isset($orderUpdatedNotificationData['user']['mail']['text']['ru']) ? $orderUpdatedNotificationData['user']['mail']['text']['ru'] : '') }}</textarea>
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="update-mail_text_uk">Текст сообщения (UA)</label>
                <textarea id="update-mail_text_uk" class="form-control" rows="4"
                          name="order_updated_mail_text_uk">{{ old('order_updated_mail_text_uk', isset($orderUpdatedNotificationData['user']['mail']['text']['uk']) ? $orderUpdatedNotificationData['user']['mail']['text']['uk'] : '') }}</textarea>
            </div>

        </div>

    </div>

    <div class="card p-4 mb-5">

        <h5 class="mb-4">SMS сообщение пользователю</h5>

        <div class="custom-control custom-checkbox mb-4">
            <input id="update-sms-user-channel" type="checkbox" name="order_updated_user_channels[]" value="{{ \App\Channels\SmsChannel::class }}"
                   class="custom-control-input multi-inputs-checkbox"{{ in_array(\App\Channels\SmsChannel::class, old('order_updated_user_channels', [])) || (isset($orderUpdatedNotificationData['user']['channels']) && in_array(\App\Channels\SmsChannel::class, $orderUpdatedNotificationData['user']['channels'])) ? ' checked' : '' }}>
            <label class="custom-control-label" for="update-sms-user-channel">Передавать по SMS каналу</label>
        </div>

        <div class="row form-group">

            <div class="col-12 col-sm-6 mb-4">
                <label for="update-sms_text_ru">Текст сообщения (RU)</label>
                <textarea id="update-sms_text_ru" class="form-control" rows="4"
                          name="order_updated_sms_text_ru">{{ old('order_updated_sms_text_ru', isset($orderUpdatedNotificationData['user']['sms']['text']['ru']) ? $orderUpdatedNotificationData['user']['sms']['text']['ru'] : '') }}</textarea>
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="update-sms_text_uk">Текст сообщения (UA)</label>
                <textarea id="update-sms_text_uk" class="form-control" rows="4"
                          name="order_updated_sms_text_uk">{{ old('order_updated_sms_text_uk', isset($orderUpdatedNotificationData['user']['sms']['text']['uk']) ? $orderUpdatedNotificationData['user']['sms']['text']['uk'] : '') }}</textarea>
            </div>

        </div>

    </div>

    <div class="card p-4 mb-5">

        <h5 class="mb-4">SMS сообщение менеджеру</h5>

        <div class="custom-control custom-checkbox mb-4">
            <input id="update-sms-manager-channel" type="checkbox" name="order_updated_manager_channels[]" value="{{ \App\Channels\SmsChannel::class }}"
                   class="custom-control-input multi-inputs-checkbox"{{ in_array(\App\Channels\SmsChannel::class, old('order_updated_manager_channels', [])) || (isset($orderUpdatedNotificationData['manager']['channels']) && in_array(\App\Channels\SmsChannel::class, $orderUpdatedNotificationData['manager']['channels'])) ? ' checked' : '' }}>
            <label class="custom-control-label" for="update-sms-manager-channel">Передавать по SMS каналу</label>
        </div>

        <div class="row form-group">

            <div class="col-12 col-sm-6 mb-4">
                <label for="update-manager_text_ru">Текст сообщения (RU)</label>
                <textarea id="update-manager_text_ru" class="form-control" rows="4"
                          name="order_updated_manager_text_ru">{{ old('order_updated_manager_text_ru', isset($orderUpdatedNotificationData['manager']['text']['ru']) ? $orderUpdatedNotificationData['manager']['text']['ru'] : '') }}</textarea>
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="update-manager_text_uk">Текст сообщения (UA)</label>
                <textarea id="update-manager_text_uk" class="form-control" rows="4"
                          name="order_updated_manager_text_uk">{{ old('order_updated_manager_text_uk', isset($orderUpdatedNotificationData['manager']['text']['uk']) ? $orderUpdatedNotificationData['manager']['text']['uk'] : '') }}</textarea>
            </div>

        </div>

    </div>

    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
</form>
