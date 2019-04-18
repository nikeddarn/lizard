<div class="d-flex justify-content-between align-items-start">
    <ul class="list-group-flush col-12 cl-sm-6 col-lg-4">
        <li class="list-group-item d-flex justify-content-between">
            <strong>Тип доставки</strong>
            <span class="ml-4">{{ $order->deliveryType->name_ru }}</span>
        </li>
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
</div>
