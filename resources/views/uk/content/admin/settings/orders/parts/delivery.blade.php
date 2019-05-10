<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="delivery_uah_price">Цена курьерской доставки (грн)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="delivery_uah_price" class="form-control"
                       type="text" name="delivery_uah_price"
                       value="{{ $ordersData['delivery']['delivery_uah_price'] }}">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="free_delivery_from_uah_sum">Бесплатная доставка от (грн)</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="free_delivery_from_uah_sum" class="form-control"
                       type="text" name="free_delivery_from_uah_sum"
                       value="{{ $ordersData['delivery']['free_delivery_from_uah_sum'] }}">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="free_delivery_from_column">Бесплатная доставка начиная с колонки</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="free_delivery_from_column" class="form-control"
                       type="text" name="free_delivery_from_column"
                       value="{{ $ordersData['delivery']['free_delivery_from_column'] }}" max="3">
            </div>
        </div>
    </div>

</div>
