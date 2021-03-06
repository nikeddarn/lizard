<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="min_order_quantity">Минимальный заказ</label>
        </div>
        <div class="col-sm-8">
            <input id="min_order_quantity" name="min_order_quantity" type="number" class="w-100" value="{{ old('min_order_quantity', 1) }}" min="1">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="price1">Цена 1 (розница, $)</label>
        </div>
        <div class="col-sm-8">
            <input id="price1" name="price1" type="text" class="w-100" value="{{ old('price1', $product->price1) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="price2">Цена 2 (постоянный клиент, $)</label>
        </div>
        <div class="col-sm-8">
            <input id="price2" name="price2" type="text" class="w-100" value="{{ old('price2', $product->price2) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="price3">Цена 3 (опт, $)</label>
        </div>
        <div class="col-sm-8">
            <input id="price3" name="price3" type="text" class="w-100" value="{{ old('price3', $product->price3) }}">
        </div>
    </div>

</div>
