<div class="card p-5 mb-4">

    <div class="alert alert-info mb-5">Товар загружается при выполнении условия по сумме <strong>или</strong> по процентам</div>

    <div class="form-group mb-4">
        <label for="min-profit-sum-to-download-product">Минимальная прибыль на товаре для загрузки в магазин
            ($)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="min-profit-sum-to-download-product" class="form-control vendor-settings-input" type="number"
                       name="min_profit_sum_to_download_product" min="0" max="100"
                       step="0.1">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="min-profit-percents-to-download-product">Минимальная прибыль на товаре для загрузки в магазин
            (%)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="min-profit-percents-to-download-product" class="form-control vendor-settings-input" type="number"
                       name="min_profit_percents_to_download_product" min="0" max="100"
                       step="0.1">
            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="download-product-max-age">Максимальный возраст товара для загрузки в магазин (мес)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="download-product-max-age" class="form-control" type="number"
                       name="download_product_max_age" min="1" max="60"
                       step="1">
            </div>
        </div>
    </div>

</div>
