<div class="modal fade" id="show-order-products-{{ $order->id }}" tabindex="-1" role="dialog"
     aria-labelledby="show-order-products-{{ $order->id }}-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <form method="post" action="{{ route('user.order.update') }}">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <div class="modal-header">
                    <h5 class="modal-title text-gray-hover" id="show-order-products-{{ $order->id }}-title">Товары
                        заказа
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
                                <td class="text-right">{{ $product->pivot->count }}&nbsp;x</td>
                                <td class="text-left">{{ number_format($product->pivot->price) }}&nbsp;грн</td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">

                    <div class="modal-products-summary">
                        <h6 class="text-gray-hover d-flex justify-content-between align-items-center">
                            <span class="bold">Итого:</span>
                            <span>{{ number_format($order->products_sum) }}&nbsp;грн</span>
                        </h6>
                        @if($order->delivery_sum > 0)
                            <h6 class="text-gray-hover d-flex justify-content-between align-items-center">
                                <span class="bold">Доставка:</span>
                                <span>{{ number_format($order->delivery_sum) }}&nbsp;грн</span>
                            </h6>
                            <h6 class="text-gray-hover d-flex justify-content-between align-items-center">
                                <span class="bold">Всего:</span>
                                <span >{{ number_format($order->total_sum) }}&nbsp;грн</span>
                            </h6>
                        @endif
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>

            </form>

        </div>
    </div>
</div>
