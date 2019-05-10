<form method="post" action="{{ route('admin.notifications.order.created.update') }}">
    @csrf

    <div class="card p-4 mb-5">

        <h5 class="mb-4">E-mail сообщение пользователю</h5>

        <div class="custom-control custom-checkbox mb-4">
            <input id="mail-user-channel" type="checkbox" name="order_created_user_channels[]" value="mail"
                   class="custom-control-input multi-inputs-checkbox"{{ in_array('mail', old('order_created_user_channels', [])) || (isset($orderCreatedNotificationData['user']['channels']) && in_array('mail', $orderCreatedNotificationData['user']['channels'])) ? ' checked' : '' }}>
            <label class="custom-control-label" for="mail-user-channel">Передавать по E-mail каналу</label>
        </div>

        <div class="row form-group">

            <div class="col-12 col-sm-6 mb-4">
                <label for="mail_header_ru">Заголовок сообщения (RU)</label>
                <input id="mail_header_ru" class="form-control" type="text" name="order_created_mail_header_ru"
                       value="{{ old('order_created_mail_header_ru', isset($orderCreatedNotificationData['user']['mail']['header']['ru']) ? $orderCreatedNotificationData['user']['mail']['header']['ru'] : '') }}">
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="mail_header_uk">Заголовок сообщения (UA)</label>
                <input id="mail_header_uk" class="form-control" type="text" name="order_created_mail_header_uk"
                       value="{{ old('order_created_mail_header_uk', isset($orderCreatedNotificationData['user']['mail']['header']['uk']) ? $orderCreatedNotificationData['user']['mail']['header']['uk'] : '') }}">
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="mail_text_ru">Текст сообщения (RU)</label>
                <textarea id="mail_text_ru" class="form-control" rows="4"
                          name="order_created_mail_text_ru">{{ old('order_created_mail_text_ru', isset($orderCreatedNotificationData['user']['mail']['text']['ru']) ? $orderCreatedNotificationData['user']['mail']['text']['ru'] : '') }}</textarea>
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="mail_text_uk">Текст сообщения (UA)</label>
                <textarea id="mail_text_uk" class="form-control" rows="4"
                          name="order_created_mail_text_uk">{{ old('order_created_mail_text_uk', isset($orderCreatedNotificationData['user']['mail']['text']['uk']) ? $orderCreatedNotificationData['user']['mail']['text']['uk'] : '') }}</textarea>
            </div>

        </div>

    </div>

    <div class="card p-4 mb-5">

        <h5 class="mb-4">SMS сообщение пользователю</h5>

        <div class="custom-control custom-checkbox mb-4">
            <input id="sms-user-channel" type="checkbox" name="order_created_user_channels[]" value="{{ \App\Channels\SmsChannel::class }}"
                   class="custom-control-input multi-inputs-checkbox"{{ in_array(\App\Channels\SmsChannel::class, old('order_created_user_channels', [])) || (isset($orderCreatedNotificationData['user']['channels']) && in_array(\App\Channels\SmsChannel::class, $orderCreatedNotificationData['user']['channels'])) ? ' checked' : '' }}>
            <label class="custom-control-label" for="sms-user-channel">Передавать по SMS каналу</label>
        </div>

        <div class="row form-group">

            <div class="col-12 col-sm-6 mb-4">
                <label for="sms_text_ru">Текст сообщения (RU)</label>
                <textarea id="sms_text_ru" class="form-control" rows="4"
                          name="order_created_sms_text_ru">{{ old('order_created_sms_text_ru', isset($orderCreatedNotificationData['user']['sms']['text']['ru']) ? $orderCreatedNotificationData['user']['sms']['text']['ru'] : '') }}</textarea>
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="sms_text_uk">Текст сообщения (UA)</label>
                <textarea id="sms_text_uk" class="form-control" rows="4"
                          name="order_created_sms_text_uk">{{ old('order_created_sms_text_uk', isset($orderCreatedNotificationData['user']['sms']['text']['uk']) ? $orderCreatedNotificationData['user']['sms']['text']['uk'] : '') }}</textarea>
            </div>

        </div>

    </div>

    <div class="card p-4 mb-5">

        <h5 class="mb-4">SMS сообщение менеджеру</h5>

        <div class="custom-control custom-checkbox mb-4">
            <input id="sms-manager-channel" type="checkbox" name="order_created_manager_channels[]" value="{{ \App\Channels\SmsChannel::class }}"
                   class="custom-control-input multi-inputs-checkbox"{{ in_array(\App\Channels\SmsChannel::class, old('order_created_manager_channels', [])) || (isset($orderCreatedNotificationData['manager']['channels']) && in_array(\App\Channels\SmsChannel::class, $orderCreatedNotificationData['manager']['channels'])) ? ' checked' : '' }}>
            <label class="custom-control-label" for="sms-manager-channel">Передавать по SMS каналу</label>
        </div>

        <div class="row form-group">

            <div class="col-12 col-sm-6 mb-4">
                <label for="manager_text_ru">Текст сообщения (RU)</label>
                <textarea id="manager_text_ru" class="form-control" rows="4"
                          name="order_created_manager_text_ru">{{ old('order_created_manager_text_ru', isset($orderCreatedNotificationData['manager']['text']['ru']) ? $orderCreatedNotificationData['manager']['text']['ru'] : '') }}</textarea>
            </div>

            <div class="col-12 col-sm-6 mb-4">
                <label for="manager_text_uk">Текст сообщения (UA)</label>
                <textarea id="manager_text_uk" class="form-control" rows="4"
                          name="order_created_manager_text_uk">{{ old('order_created_manager_text_uk', isset($orderCreatedNotificationData['manager']['text']['uk']) ? $orderCreatedNotificationData['manager']['text']['uk'] : '') }}</textarea>
            </div>

        </div>

    </div>

    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
</form>
