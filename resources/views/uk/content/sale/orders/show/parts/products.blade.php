<div class="modal-body full-cart">

    <table class="table table-sm">
        <thead class="thead-light">
        <tr class="text-center">
            <td>Изображение</td>
            <td>Название</td>
            <td>Количество</td>
            <td>Цена, грн</td>
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
            </tr>

        @endforeach

        </tbody>
    </table>

</div>
