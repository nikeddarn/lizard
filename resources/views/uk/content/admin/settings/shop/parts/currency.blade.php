<div class="card p-5 mb-5">

    <div class="custom-control custom-radio mb-4">
        <input type="radio" id="auto-currency-rate" name="get_exchange_rate" value="auto"
               class="custom-control-input"{{ $currenciesData['exchange_rate']['get_exchange_rate'] === 'auto' ? ' checked' : '' }}>
        <label class="custom-control-label" for="auto-currency-rate">Получать курс валют автоматически</label>
    </div>


    <div class="custom-control custom-radio mb-4">
        <input type="radio" id="manual-currency-rate" name="get_exchange_rate" value="manual"
               class="custom-control-input"{{ $currenciesData['exchange_rate']['get_exchange_rate'] === 'manual' ? ' checked' : '' }}>
        <label class="custom-control-label" for="manual-currency-rate">Установить курс валют вручную</label>
    </div>

    <div class="row">

        <div id="update-rate-time-wrapper"
             class="col col-sm-6 col-md-5 col-xl-3 d-none{{ $currenciesData['exchange_rate']['get_exchange_rate'] === 'auto' ? ' d-block' : '' }}">
            <label for="update-rate-time">Частота обновления (мин)</label>
            <input id="update-rate-time" name="update_rate_time" value="{{ $currenciesData['exchange_rate']['ttl'] }}" type="number"
                   step="1">
        </div>

        <div id="set-usd-rate-wrapper"
             class="col col-sm-6 col-md-5 col-xl-3 d-none{{ $currenciesData['exchange_rate']['get_exchange_rate'] === 'manual' ? ' d-block' : '' }}">
            <label for="manual-usd-rate">Курс USD</label>
            <input id="manual-usd-rate" name="usd_rate" value="{{ $currenciesData['exchange_rate']['usd_rate'] }}" type=number step=0.01>
        </div>

    </div>

</div>

<div class="card p-5 mb-5">

    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" id="show-usd-price" name="allow_usd_price"
               class="custom-control-input multi-inputs-checkbox"{{ $currenciesData['show_usd_price']['allowed'] ? ' checked' : '' }}>
        <label class="custom-control-label" for="show-usd-price">Показывать USD цены выбранным группам
            пользователей</label>
    </div>

    <div class="row">

        <div class="col col-sm-6 col-md-5 col-xl-3 multi-inputs-related-field{{ $currenciesData['show_usd_price']['allowed'] ? ' visible' : ' invisible' }}">
            <label for="min-user-price-group">Показывать начиная с прайс-группы</label>
            <input id="min-user-price-group" name="min_user_price_group"
                   value="{{ $currenciesData['show_usd_price']['min_user_price_group'] }}" type=number min="1" max="3">
        </div>

    </div>

</div>
