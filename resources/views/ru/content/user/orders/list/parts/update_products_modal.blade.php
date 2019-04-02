<div class="modal fade" id="update-order-products-{{ $order->id }}" tabindex="-1" role="dialog"
     aria-labelledby="update-order-products-{{ $order->id }}-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <form method="post" action="{{ route('user.order.update') }}">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <div class="modal-header">
                    <h5 class="modal-title text-gray-hover" id="update-order-products-{{ $order->id }}-title">Изменение заказа
                        (Id
                        заказа: <strong>{{ $order->id }}</strong>)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body full-cart">

                    <table class="table table-borderless table-cart table-sm">
                        <tbody>

                        @foreach($order->products as $product)

                            <tr>
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
                                <td class="d-inline-block d-md-table-cell cart-qty nostretch text-center">
                                    <div class="spinner">
                                        <button type="button" class="product-decrease btn btn-icon rounded-circle">
                                            <i class="svg-icon-larger" data-feather="minus"></i>
                                        </button>
                                        <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                        <input type="number" class="form-control product-count" name="count[]"
                                               value="{{ $product->pivot->count }}" min="1" max="999">
                                        <button type="button" class="product-increase btn btn-icon rounded-circle">
                                            <i class="svg-icon-larger" data-feather="plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="d-table-cell cart-action nostretch pr-0">
                                    <button class="product-remove text-danger close" title="Удалить">
                                        <i class="svg-icon-larger" data-feather="x-circle"></i>
                                    </button>
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                </div>

            </form>

        </div>
    </div>
</div>
