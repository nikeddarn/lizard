<div class="row">
    <div class="col-12 col-xl-7 mb-4">

        <div class="card card-body">

            <ul class="list-group-flush m-0 p-0">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Минимальная прибыль на товаре для загрузки в магазин ($)</span>
                    <span>{{ $vendorCategory->download_product_min_profit_sum }}</span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Минимальная прибыль на товаре для загрузки в магазин (%)</span>
                    <span>{{ $vendorCategory->download_product_min_profit_percent }}</span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Максимальный возраст товара для загрузки в магазин (мес)</span>
                    <span>{{ $vendorCategory->download_product_max_age }}</span>
                </li>
            </ul>

        </div>

    </div>

    <div class="col-12 col-xl-7 mb-4">

        <div class="card card-body">

            <ul class="list-group-flush m-0 p-0">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Минимальная прибыль на товаре для публикации в магазине ($)</span>
                    <span>{{ $vendorCategory->publish_product_min_profit_sum }}</span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Минимальная прибыль на товаре для публикации в магазине (%)</span>
                    <span>{{ $vendorCategory->publish_product_min_profit_percent }}</span>
                </li>
            </ul>

        </div>

    </div>
</div>
