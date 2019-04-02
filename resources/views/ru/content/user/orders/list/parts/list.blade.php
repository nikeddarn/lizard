<div class="table-responsive my-4">

    <table class="table table-hover table-sm">
        <thead class="thead-light">
        <tr>
            <td class="text-center">Id заказа</td>
            <td class="text-center">Дата</td>
            <td class="text-center">Всего</td>
            <td class="text-center">Статус</td>
            <td></td>
        </tr>
        </thead>

        <tbody>
        @foreach($orders as $order)

            <tr>
                <td class="h6 text-center bold">
                    @can('view', $order)
                        <a class="d-block cursor-pointer" data-toggle="modal"
                           data-target="#show-order-products-{{ $order->id }}">{{ $order->id }}</a>
                    @else
                        <span>{{ $order->id }}</span>
                    @endcan
                </td>
                <td class="h6 text-center text-gray-hover">{{ $order->created_at->formatLocalized('%e %B %Y') }}</td>
                <td class="h6 text-center text-gray-hover">{{ number_format($order->total_sum) }} грн</td>
                <td class="text-center">
                    <span
                        class="badge-bigger badge badge-{{ $order->orderStatus->class }}">{{ $order->orderStatus->name_ru }}</span>
                </td>
                <td>
                    <div class="d-flex justify-content-end align-items-center">
                        @can('view', $order)
                            <button type="button" class="btn btn-sm btn-outline-primary btn-no-border p-1"
                                    data-toggle="modal" data-target="#show-order-products-{{ $order->id }}">
                                <i class="svg-icon-larger" data-feather="eye"></i>
                            </button>
                        @endcan
                        @can('update', $order)
                            <button type="button" class="btn btn-sm btn-outline-primary btn-no-border p-1"
                                    data-toggle="modal" data-target="#update-order-products-{{ $order->id }}">
                                <i class="svg-icon-larger" data-feather="edit"></i>
                            </button>
                        @endcan
                        @can('cancel', $order)
                            <form class="cancel-order" action="{{ route('user.order.cancel') }}" method="post">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-no-border p-1"
                                        data-toggle="tooltip"
                                        title="Отменить">
                                    <i class="svg-icon-larger" data-feather="trash-2"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>

        @endforeach
        </tbody>

    </table>

</div>
