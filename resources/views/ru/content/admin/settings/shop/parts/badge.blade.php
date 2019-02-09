<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="badge-new">Показывать стикер "Новый товар" (дней)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="badge-new" class="form-control default-bootstrap-select-input" type="number"
                       name="new_product_badge_ttl"
                       value="{{ $badgesData[\App\Contracts\Shop\ProductBadgesInterface::NEW] }}" min="1"
                       max="100">
            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="badge-price-down">Показывать стикер "Снижение цены" (дней)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="badge-price-down" class="form-control default-bootstrap-select-input" type="number"
                       name="price_down_badge_ttl"
                       value="{{ $badgesData[\App\Contracts\Shop\ProductBadgesInterface::PRICE_DOWN] }}" min="1"
                       max="100">
            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="badge-ending">Показывать стикер "Товар заканчивается" при наличии меньше (шт)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="badge-ending" class="form-control default-bootstrap-select-input" type="number"
                       name="ending_badge_products_count"
                       value="{{ $badgesData[\App\Contracts\Shop\ProductBadgesInterface::ENDING] }}" min="1"
                       max="100">
            </div>
        </div>
    </div>

</div>
