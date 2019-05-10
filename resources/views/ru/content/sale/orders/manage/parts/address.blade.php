<div class="d-flex justify-content-between align-items-start">
    <ul class="list-group-flush col-12 cl-sm-6 col-lg-4">
        <li class="list-group-item d-flex justify-content-between">
            <strong>Тип доставки</strong>
            <span class="ml-4">{{ $order->deliveryType->name_ru }}</span>
        </li>
        @if($order->storage)
            <li class="list-group-item d-flex justify-content-between">
                <strong>Склад самовывоза</strong>
                <span class="ml-4">{{ $order->storage->name_ru }} ({{ $order->storage->city->name_ru }})</span>
            </li>
        @endif
        @if($order->orderAddress)
            @if($order->orderAddress->city)
                <li class="list-group-item d-flex justify-content-between">
                    <strong>Город</strong>
                    <span class="ml-4">{{ $order->orderAddress->city->name_ru }}</span>
                </li>
            @endif
            <li class="list-group-item d-flex justify-content-between">
                <strong>Адрес</strong>
                <span class="ml-4">{{ $order->orderAddress->address }}</span>
            </li>
        @endif
    </ul>

    @can('updateDelivery', $order)
        <a href="{{ route('admin.order.address.edit', ['order_id' => $order->id]) }}" class="btn btn-primary"
           title="Изменить">
            <i class="svg-icon-larger" data-feather="edit"></i>
        </a>
    @endcan
</div>
