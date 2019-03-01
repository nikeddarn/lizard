<div class="card p-5 mb-4">

    <div class="alert alert-info mb-5">Товар публикуется при выполнении условия по сумме <strong>или</strong> по
        процентам
    </div>

    <div class="form-group mb-4">
        <label for="min-profit-sum-to-publish-product">Минимальная прибыль на товаре для публикации в магазине
            ($)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="min-profit-sum-to-publish-product" class="form-control vendor-settings-input" type="number"
                       name="min_profit_sum_to_publish_product"
                       value="{{ old('min_profit_sum_to_publish_product', $vendorCategory->publish_product_min_profit_sum) }}" min="0"
                       max="100" step="0.1">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="min-profit-percents-to-publish-product">Минимальная прибыль на товаре для публикации в магазине
            (%)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="min-profit-percents-to-publish-product" class="form-control vendor-settings-input"
                       type="number"
                       name="min_profit_percents_to_publish_product" value="{{ old('min_profit_percents_to_publish_product', $vendorCategory->publish_product_min_profit_percent) }}" min="0" max="100"
                       step="0.1">
            </div>
        </div>
    </div>

</div>
