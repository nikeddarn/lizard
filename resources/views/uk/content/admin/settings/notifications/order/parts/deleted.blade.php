<form method="post" action="{{ route('admin.notifications.order.deleted.update') }}">
    @csrf

    <div class="card p-4 mb-5">

        <h5 class="mb-4">E-mail сообщение пользователю</h5>

        <div class="custom-control custom-checkbox mb-4">
            <input id="delete-mail-user-channel" type="checkbox" name="order_deleted_user_channels[]" value="mail"
                   class="custom-control-input multi-inputs-checkbox"{{ in_array('mail', old('order_deleted_user_channels', [])) || (isset($orderDeletedNotificationData['user']['channels']) && in_array('mail', $orderDeletedNotificationData['user']['channels'])) ? ' checked' : '' }}>
            <label class="custom-control-label" for="delete-mail-user-channel">Передавать по E-mail каналу</label>
        </div>

        <div class="row form-group">

            <div class="col-12 col-sm-6 mb-4">
                <label for="delete-mail_header_ru">Заголовок сообщения (RU)</label>
                <input id="delete-mail_header_ru" class="form-control" type="text" name="order_deleted_mail_header_ru"
                       value="{{ old('order_deleted_mail_header_ru', isset($orderDeletedNotificationData['user']['mail']['header']['ru']) ? $orderDeletedNotificationData['user']['mail']['header']['ru'] : '') }}">
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="delete-mail_header_uk">Заголовок сообщения (UA)</label>
                <input id="delete-mail_header_uk" class="form-control" type="text" name="order_deleted_mail_header_uk"
                       value="{{ old('order_deleted_mail_header_uk', isset($orderDeletedNotificationData['user']['mail']['header']['uk']) ? $orderDeletedNotificationData['user']['mail']['header']['uk'] : '') }}">
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="delete-mail_text_ru">Текст сообщения (RU)</label>
                <textarea id="delete-mail_text_ru" class="form-control" rows="4"
                          name="order_deleted_mail_text_ru">{{ old('order_deleted_mail_text_ru', isset($orderDeletedNotificationData['user']['mail']['text']['ru']) ? $orderDeletedNotificationData['user']['mail']['text']['ru'] : '') }}</textarea>
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="delete-mail_text_uk">Текст сообщения (UA)</label>
                <textarea id="delete-mail_text_uk" class="form-control" rows="4"
                          name="order_deleted_mail_text_uk">{{ old('order_deleted_mail_text_uk', isset($orderDeletedNotificationData['user']['mail']['text']['uk']) ? $orderDeletedNotificationData['user']['mail']['text']['uk'] : '') }}</textarea>
            </div>

        </div>

    </div>

    <div class="card p-4 mb-5">

        <h5 class="mb-4">SMS сообщение пользователю</h5>

        <div class="custom-control custom-checkbox mb-4">
            <input id="delete-sms-user-channel" type="checkbox" name="order_deleted_user_channels[]" value="{{ \App\Channels\SmsChannel::class }}"
                   class="custom-control-input multi-inputs-checkbox"{{ in_array(\App\Channels\SmsChannel::class, old('order_deleted_user_channels', [])) || (isset($orderDeletedNotificationData['user']['channels']) && in_array(\App\Channels\SmsChannel::class, $orderDeletedNotificationData['user']['channels'])) ? ' checked' : '' }}>
            <label class="custom-control-label" for="delete-sms-user-channel">Передавать по SMS каналу</label>
        </div>

        <div class="row form-group">

            <div class="col-12 col-sm-6 mb-4">
                <label for="delete-sms_text_ru">Текст сообщения (RU)</label>
                <textarea id="delete-sms_text_ru" class="form-control" rows="4"
                          name="order_deleted_sms_text_ru">{{ old('order_deleted_sms_text_ru', isset($orderDeletedNotificationData['user']['sms']['text']['ru']) ? $orderDeletedNotificationData['user']['sms']['text']['ru'] : '') }}</textarea>
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="delete-sms_text_uk">Текст сообщения (UA)</label>
                <textarea id="delete-sms_text_uk" class="form-control" rows="4"
                          name="order_deleted_sms_text_uk">{{ old('order_deleted_sms_text_uk', isset($orderDeletedNotificationData['user']['sms']['text']['uk']) ? $orderDeletedNotificationData['user']['sms']['text']['uk'] : '') }}</textarea>
            </div>

        </div>

    </div>

    <div class="card p-4 mb-5">

        <h5 class="mb-4">SMS сообщение менеджеру</h5>

        <div class="custom-control custom-checkbox mb-4">
            <input id="delete-sms-manager-channel" type="checkbox" name="order_deleted_manager_channels[]" value="{{ \App\Channels\SmsChannel::class }}"
                   class="custom-control-input multi-inputs-checkbox"{{ in_array(\App\Channels\SmsChannel::class, old('order_deleted_manager_channels', [])) || (isset($orderDeletedNotificationData['manager']['channels']) && in_array(\App\Channels\SmsChannel::class, $orderDeletedNotificationData['manager']['channels'])) ? ' checked' : '' }}>
            <label class="custom-control-label" for="delete-sms-manager-channel">Передавать по SMS каналу</label>
        </div>

        <div class="row form-group">

            <div class="col-12 col-sm-6 mb-4">
                <label for="delete-manager_text_ru">Текст сообщения (RU)</label>
                <textarea id="delete-manager_text_ru" class="form-control" rows="4"
                          name="order_deleted_manager_text_ru">{{ old('order_deleted_manager_text_ru', isset($orderDeletedNotificationData['manager']['text']['ru']) ? $orderDeletedNotificationData['manager']['text']['ru'] : '') }}</textarea>
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="delete-manager_text_uk">Текст сообщения (UA)</label>
                <textarea id="delete-manager_text_uk" class="form-control" rows="4"
                          name="order_deleted_manager_text_uk">{{ old('order_deleted_manager_text_uk', isset($orderDeletedNotificationData['manager']['text']['uk']) ? $orderDeletedNotificationData['manager']['text']['uk'] : '') }}</textarea>
            </div>

        </div>

    </div>

    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
</form>
