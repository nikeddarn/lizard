<form id="order-recipient-edit-form" action="{{ route('admin.order.recipient.update') }}" method="post">
    @csrf
    <input type="hidden" name="order_id" value="{{ $order->id }}">

    <div class="row">

        <div class="col-12 col-sm-6 col-lg-4 offset-sm-1 offset-lg-2">

            <div class="form-group">
                <label class="bold text-gray-hover" for="name">Имя</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="{{ $order->orderRecipient->name }}">
                @if ($errors->has('name'))
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                @endif
            </div>

            <div class="form-group">
                <label class="bold text-gray-hover" for="phone">Телефон</label>
                <input type="text" class="form-control" id="phone" name="phone"
                       value="{{ $order->orderRecipient->phone }}">
                @if ($errors->has('phone'))
                    <small class="text-danger">{{ $errors->first('phone') }}</small>
                @endif
            </div>

            @can('updateDelivery', $order)
                <div class="text-right mt-5 mb-4">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            @endcan

        </div>

    </div>

</form>
