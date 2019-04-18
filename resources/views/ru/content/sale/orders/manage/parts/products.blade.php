<div class="modal-body full-cart">

    <table class="table table-sm">
        <thead class="thead-light">
        <tr class="text-center">
            <td>Изображение</td>
            <td>Название</td>
            <td>Количество</td>
            <td>Цена, грн</td>
            <td></td>
        </tr>
        </thead>
        <tbody>

        @foreach($order->products as $product)

            <tr class="text-center">
                <td class="d-inline-block d-md-table-cell cart-img nostretch">
                    @if($product->primaryImage)
                        <a href="{{ route('shop.product.index', ['url' => $product->url, 'locale' => $locale,]) }}">
                            <img src="{{ url('/storage/' . $product->primaryImage->small) }}"
                                 alt="{{ $product->name_ru }}">
                        </a>
                    @endif
                </td>
                <td class="d-inline-block d-md-table-cell cart-title">
                    <a href="{{ route('shop.product.index', ['url' => $product->url, 'locale' => $locale,]) }}">
                        <span class="text-gray-hover">{{ $product->name_ru }}</span>
                    </a>
                </td>
                <td>{{ $product->pivot->count }}</td>
                <td>{{ $product->pivot->price }}</td>
                <td>
                    @can('update', $order)
                        <div class="d-flex justify-content-center align-items-start">
                            <a href="{{ route('admin.order.product.edit', ['order_id' => $order->id, 'product_id' => $product->id]) }}"
                               class="btn btn-primary" title="Изменить">
                                <i class="svg-icon-larger" data-feather="edit"></i>
                            </a>
                            <form class="delete-product ml-1" method="post"
                                  action="{{ route('admin.order.product.delete') }}">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button class="btn btn-danger" title="Удалить">
                                    <i class="svg-icon-larger" data-feather="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    @endcan
                </td>
            </tr>

        @endforeach

        </tbody>
    </table>

    @can('update', $order)
        <div class="text-right mt-5">
            <a href="{{ route('admin.order.product.create', ['order_id' =>$order->id]) }}" class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="plus"></i>
                <span>Добавить товар</span>
            </a>
        </div>
    @endcan

</div>
