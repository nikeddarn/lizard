<div class="card card-body">
    <div class="table-responsive">
        <table id="category-products" class="table table-sm table-striped">
            <thead class="thead-light">
            <tr class="text-center">
                <td>Найменування</td>
                <td>Наявність</td>
                <td>Ціна (група&nbsp;{{ $userPriceGroup }})</td>
                <td>Ціна, грн</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>

                    <td>{{ $product->name_uk }}</td>

                    <td>
                        <div class="text-gray-hover">
                            @if(!empty($product->isAvailable))
                                <i class="svg-icon text-success" data-feather="check-circle"></i>
                                <span class="ml-2">Готов к отгрузке</span>
                            @elseif(!empty($product->isExpectedToday))
                                <i class="svg-icon text-warning" data-feather="clock"></i>
                                <span class="ml-2">Ожидается сегодня</span>
                            @elseif(!empty($product->isExpectedTomorrow))
                                <i class="svg-icon text-warning" data-feather="clock"></i>
                                <span class="ml-2">Ожидается завтра</span>
                            @elseif(!empty($product->expectedAt))
                                <i class="svg-icon text-warning" data-feather="clock"></i>
                                <span class="ml-2">Ожидается {{ $product->expectedAt->diffForHumans() }}</span>
                            @else
                                <i class="svg-icon text-danger" data-feather="alert-circle"></i>
                                <span class="ml-2">Нет в наличии</span>
                            @endif
                        </div>
                    </td>

                    <td class="text-center">{{ $product->price }}</td>

                    <td class="text-center">{{ $product->localPrice }}</td>

                    <td class="text-right">
                        @can('update', $order)
                            @if($product->cartAble)
                                <form method="post" action="{{ route('admin.order.product.store') }}">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="svg-icon-larger" data-feather="plus"></i>
                                        <span>Добавить</span>
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
