@foreach($recentProducts as $product)

    <div class="media mb-4 border-bottom">

        <a href="{{ $product->href }}" class="mr-3 w-25">
            @if($product->primaryImage)
                <img class="img-fluid" src="{{ url('/storage/' . $product->primaryImage->medium) }}"
                     alt="{{ $product->name }}">
            @endif
        </a>

        <div class="media-body align-self-center justify-content-center p-2">
            <a href="{{ $product->href }}" class="h6 mb-2 d-block">{{ $product->name }}</a>
            <div class="text-gray-hover mb-2">
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

            @if($product->localPrice)
                <ul class="card-text list-inline">
                    <li class="list-inline-item">
                        <span class="product-price h5">{{ $product->localPrice }}&nbsp;грн</span>
                    </li>
                    @if($product->price)
                        <li class="list-inline-item">
                            <span>${{ $product->price }}</span>
                        </li>
                    @endif
                </ul>
            @endif

            @if($product->cartAble)
                <a href="{{ route('shop.cart.add', ['id' => $product->id]) }}"
                   class="btn btn-outline-primary rounded-pill">Добавить в корзину</a>
            @endif

        </div>

    </div>

@endforeach
