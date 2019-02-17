<div class="card p-5 mb-4">

    <div class="alert alert-info mb-5">Товар публикуется при выполнении условия по сумме <strong>или</strong> по процентам</div>

    <div class="form-group mb-4">
        <label for="min-profit-sum-to-offer-product">Минимальная прибыль на товаре для публикации в магазине
            ($)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="min-profit-sum-to-offer-product" class="form-control vendor-settings-input" type="number"
                       name="min_profit_sum_to_offer_product"
                       value="{{ $vendorsData['min_profit_sum_to_offer_product'] }}" min="0.1" max="50"
                       step="0.1">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="min-profit-percents-to-offer-product">Минимальная прибыль на товаре для публикации в магазине
            (%)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="min-profit-percents-to-offer-product" class="form-control vendor-settings-input" type="number"
                       name="min_profit_percents_to_offer_product"
                       value="{{ $vendorsData['min_profit_percents_to_offer_product'] }}" min="0.1" max="50"
                       step="0.1">
            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-4">

    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" id="update-own-product-price-on-vendor-sync"
               name="update_own_product_price_on_vendor_sync"
               class="custom-control-input"{{ $vendorsData['update_own_product_price_on_vendor_sync'] ? ' checked' : '' }}>
        <label class="custom-control-label" for="update-own-product-price-on-vendor-sync">Изменять цену продукта,
            который есть в наличии на собственни складе при изменении цены у поставщика</label>
    </div>

</div>

<div class="card p-5 mb-4">

    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" id="use-vendor-available-product-to-calculate-price"
               name="use_vendor_available_product_to_calculate_price"
               class="custom-control-input"{{ $vendorsData['use_vendor_available_product_to_calculate_price'] ? ' checked' : '' }}>
        <label class="custom-control-label" for="use-vendor-available-product-to-calculate-price">Учитывать цену
            поставщика при формировании цены на товар только при наличии продукта на его складе</label>
    </div>

</div>

<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="min-profit-sum-to-price-discount">Минимальная прибыль для формирования скидки по ценовым колонкам
            ($)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="min-profit-sum-to-price-discount" class="form-control vendor-settings-input"
                       type="number"
                       name="min_profit_sum_to_price_discount"
                       value="{{ $vendorsData['min_profit_sum_to_price_discount'] }}" min="0.1" max="50"
                       step="0.1">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="min-profit-percents-to-price-discount">Минимальная прибыль для формирования скидки по ценовым
            колонкам (%)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="min-profit-percents-to-price-discount" class="form-control vendor-settings-input"
                       type="number"
                       name="min_profit_percents_to_price_discount"
                       value="{{ $vendorsData['min_profit_percents_to_price_discount'] }}" min="0.1"
                       max="50" step="0.1">
            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="discount-price1">Скидка от прибыли для колонки 1 (%)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="discount-price1" class="form-control vendor-settings-input-discount" type="number"
                       name="discount_price1"
                       value="{{ $vendorsData['column_discounts']['price1'] }}" min="-50" max="50"
                       step="0.1">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="discount-price2">Скидка от прибыли для колонки 2 (%)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="discount-price2" class="form-control vendor-settings-input-discount" type="number"
                       name="discount_price2"
                       value="{{ $vendorsData['column_discounts']['price2'] }}" min="-50" max="50"
                       step="0.1">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="discount-price3">Скидка от прибыли для колонки 3 (%)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="discount-price3" class="form-control vendor-settings-input-discount" type="number"
                       name="discount_price3"
                       value="{{ $vendorsData['column_discounts']['price3'] }}" min="-50" max="50"
                       step="0.1">
            </div>
        </div>
    </div>

</div>
