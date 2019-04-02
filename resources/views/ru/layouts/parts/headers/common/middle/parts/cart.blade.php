<div class="small-cart">
    @foreach($cartData['products'] as $product)

        <div class="media">

            <a href="{{ route('shop.product.index', ['url' => $product->url, 'locale' => app()->getLocale()]) }}">
                <img src="{{ url('/storage/' . $product->primaryImage->small) }}" width="50" height="50"
                     alt="Изображение {{ $product->name_ru }}">
            </a>

            <div class="media-body">
                <a href="{{ route('shop.product.index', ['url' => $product->url, 'locale' => app()->getLocale()]) }}"
                   title="{{ $product->name_ru }}">{{ $product->name_ru }}</a>
                <span class="qty">{{ $product->pivot->count }}</span>
                <span> x </span>
                <span class="price">{{ $product->formattedLocalPrice }} грн</span>
                <a class="text-danger close" href="{{ route('shop.cart.remove', ['id' => $product->id]) }}">
                    <i class="svg-icon-larger" data-feather="x-circle"></i>
                </a>
            </div>

        </div>

    @endforeach

    <div class="cart-amount d-flex justify-content-between pb-3 pt-2">
        <span>Всего</span>
        <strong>{{ $cartData['amount'] }} грн</strong>
    </div>

    <div class="d-flex justify-content-between pb-2">
        <div class="w-100 mr-1">
            <a href="{{ route('shop.cart.index', ['locale' => app()->getLocale() === config('app.canonical_locale') ? null : app()->getLocale()]) }}"
               class="btn btn-block rounded-pill btn-secondary p-1">В корзину</a>
        </div>
        <div class="w-100 ml-1">
            <a href="{{ route('shop.checkout.create', ['locale' => app()->getLocale() === config('app.canonical_locale') ? null : app()->getLocale()]) }}"
               class="btn btn-block rounded-pill btn-primary p-1">Оформить</a>
        </div>
    </div>
</div>
