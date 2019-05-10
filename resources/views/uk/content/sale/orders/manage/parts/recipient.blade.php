<div class="d-flex justify-content-between align-items-start">
    <ul class="list-group-flush col-12 cl-sm-6 col-lg-4">
        <li class="list-group-item d-flex justify-content-between">
            <strong>Имя</strong>
            <span class="ml-4">{{ $order->orderRecipient->name }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
            <strong>Телефон</strong>
            <span class="ml-4">{{ $order->orderRecipient->phone }}</span>
        </li>
    </ul>

    @can('updateDelivery', $order)
        <a href="{{ route('admin.order.recipient.edit', ['order_id' => $order->id]) }}" class="btn btn-primary"
           title="Изменить">
            <i class="svg-icon-larger" data-feather="edit"></i>
        </a>
    @endcan
</div>
