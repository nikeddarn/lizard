@if($products->count())

    <table class="table table-borderless table-cart">
        <tbody>

        @foreach($products as $product)

            <tr>
                <td class="d-inline-block d-md-table-cell cart-img nostretch">
                    <a href="{{ $product->href }}">
                        <img src="{{ url('/storage/' . $product->primaryImage->small) }}" alt="{{ $product->name_ru }}">
                    </a>
                </td>
                <td class="d-inline-block d-md-table-cell cart-title">
                    <a href="{{ $product->href }}" class="bold d-inline-block" title="{{ $product->name_ru }}">
                        <span class="text-gray">{{ $product->name_ru }}</span>
                    </a>
                    <div class="text-gray-hover">
                        @if($product->isAvailable)
                            <i class="svg-icon text-success" data-feather="check-circle"></i>
                            <span class="ml-2">Готов к отгрузке</span>
                        @elseif($product->isExpectedToday)
                            <i class="svg-icon text-warning" data-feather="clock"></i>
                            <span class="ml-2">Ожидается сегодня</span>
                        @elseif($product->expectedAt)
                            <i class="svg-icon text-warning" data-feather="clock"></i>
                            <span class="ml-2">Ожидается {{ $product->expectedAt->diffForHumans() }}</span>
                        @else
                            <i class="svg-icon text-danger" data-feather="alert-circle"></i>
                            <span class="ml-2">Нет в наличии</span>
                        @endif
                    </div>

                </td>
                <td class="d-inline-block d-md-table-cell cart-qty nostretch text-center">
                    <div class="spinner">
                        <a href="{{ route('shop.cart.decrement', ['id' => $product->id]) }}" class="change-count">
                            <button type="button" class="btn btn-icon rounded-circle">
                                <i class="svg-icon-larger" data-feather="minus"></i>
                            </button>
                        </a>
                        <input type="number" class="form-control" value="{{ $product->pivot->count }}" min="1"
                               max="999">
                        <a href="{{ route('shop.cart.increment', ['id' => $product->id]) }}" class="change-count">
                            <button type="button" class="btn btn-icon rounded-circle">
                                <i class="svg-icon-larger" data-feather="plus"></i>
                            </button>
                        </a>
                    </div>
                </td>
                <td class="d-inline-block d-md-table-cell cart-price text-right">
                    <span class="bold text-gray-hover">{{ $product->formattedLocalPrice }} грн</span>
                </td>
                <td class="d-table-cell cart-action nostretch pr-0">
                    <a class="text-danger close" href="{{ route('shop.cart.remove', ['id' => $product->id]) }}"
                       title="Удалить">
                        <i class="svg-icon-larger" data-feather="x-circle"></i>
                    </a>
                </td>
            </tr>

        @endforeach

        </tbody>
    </table>

    <div class="text-center">
        <small class="counter">ИТОГО</small>
        <h3 class="bold text-gray-hover">{{ $amount }} грн</h3>
        <a href="{{ route('shop.checkout.index', ['locale' => app()->getLocale() === config('app.canonical_locale') ? null : app()->getLocale()]) }}"
           class="btn btn-primary rounded-pill btn-lg">
            <span>Доставка</span>
            <i class="svg-icon-larger" data-feather="arrow-right"></i>
        </a>
    </div>

@else

    <div class="text-center">
        <h3 class="text-gray-hover mb-5">Ваша корзина пуста</h3>
        <a href="{{ preg_match('/category|product/', url()->previous()) ? url()->previous() : route('main') }}"
           class="btn btn-primary rounded-pill btn-lg">
            <i class="svg-icon-larger" data-feather="arrow-left"></i>
            <span>Назад в магазин</span>
        </a>
    </div>

@endif
