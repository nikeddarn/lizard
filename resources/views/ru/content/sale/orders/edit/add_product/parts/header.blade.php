<div class="card card-body mb-4">

    <div class="d-flex justify-content-between">

        <h1 class="h4 text-gray-hover">
            <span>Заказ Id: <strong>{{ $order->id }}</strong></span>
            <small class="ml-4">Добавление товара</small>
        </h1>

        <a href="{{ route('admin.order.manage', ['order_id' => $order->id]) }}" data-toggle="tooltip" title="Назад"
           class="btn btn-primary ml-2">
            <i class="svg-icon-larger" data-feather="corner-up-left"></i>
        </a>

    </div>

</div>
