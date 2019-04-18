<div class="card card-body mb-4">

    <div class="d-flex justify-content-between">

        <h1 class="h4 text-gray-hover">
            <span>Заказ Id: <strong>{{ $order->id }}</strong></span>
            <small class="ml-4">Создан: {{ $order->created_at->diffForHumans() }}</small>
            <small class="ml-2">
                <small class="badge badge-{{ $order->orderStatus->class }}">{{ $order->orderStatus->name_ru }}</small>
            </small>
        </h1>

        <div class="d-inline-flex align-items-start">
            @can('collect', $order)
                <form class="collect-order" action="{{ route('admin.order.collect') }}" method="post">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <button type="submit" class="btn btn-success" data-toggle="tooltip" title="Собрать заказ">
                        <i class="svg-icon-larger" data-feather="skip-forward"></i>
                    </button>
                </form>
            @endcan

            @can('cancel', $order)
                <form class="cancel-order ml-2" action="{{ route('admin.order.cancel') }}" method="post">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Отменить">
                        <i class="svg-icon-larger" data-feather="trash-2"></i>
                    </button>
                </form>
            @endcan

            <a href="{{ route('admin.orders.index') }}" data-toggle="tooltip" title="К списку заказов"
               class="btn btn-primary ml-2">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>
        </div>

    </div>

</div>
