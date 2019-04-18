<form id="order-product-edit-form" action="{{ route('admin.order.product.update') }}" method="post">
    @csrf
    <input type="hidden" name="order_id" value="{{ $order->id }}">
    <input type="hidden" name="product_id" value="{{ $order->products->first()->id }}">

    <div class="row">

        <div class="col-12 col-sm-6 col-lg-4 offset-sm-1 offset-lg-2">

            <div class="form-group mb-4">
                <label class="bold text-gray-hover" for="count">Количество</label>
                <input id="count" class="form-control default-bootstrap-select-input" type="number"
                       name="count" value="{{ $order->products->first()->pivot->count }}" min="1" max="999">
                @if ($errors->has('count'))
                    <small class="text-danger">{{ $errors->first('count') }}</small>
                @endif
            </div>

            <div class="form-group">
                <label class="bold text-gray-hover" for="price">Цена, грн</label>
                <input type="text" class="form-control" id="price" name="price"
                       value="{{ $order->products->first()->pivot->price }}">
                @if ($errors->has('price'))
                    <small class="text-danger">{{ $errors->first('price') }}</small>
                @endif
            </div>

            <div class="text-right mt-5 mb-4">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>

        </div>

    </div>

</form>
