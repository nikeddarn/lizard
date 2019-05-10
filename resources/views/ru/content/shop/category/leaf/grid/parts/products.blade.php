@foreach($products as $product)

    <div class="col-6 col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-4 product-wrapper">

        <div class="h-100 card card-product">

            <a href="{{ route('user.favourites.remove', ['id' => $product->id]) }}"
               class="product-favourite product-favourite-remove align-items-center justify-content-center{{ $product->isFavourite ?  ' active d-flex' : ' d-none'}}"
               title="Удалить из избранного" data-add-title="Удалить из избранного"
               data-remove-title="Удалить из избранного">
                <i class="svg-icon" data-feather="heart"></i>
            </a>

            <a href="{{ route('user.favourites.add', ['id' => $product->id]) }}"
               class="product-favourite product-favourite-add align-items-center justify-content-center{{ $product->isFavourite ?  ' d-none' : ' d-flex'}}"
               title="Добавить в избранное" data-remove-title="Добавить в избранное"
               data-add-title="Добавить в избранное">
                <i class="svg-icon" data-feather="heart"></i>
            </a>

            @if($product->productVideos->count())
                <a href="{{ $product->href }}#product-video" class="product-video-review btn btn-link d-none d-md-block"
                   title="Смотреть видеообзор">
                    <i class="svg-icon" data-feather="video"></i>
                </a>
            @endif

            <button class="product-quickview btn btn-link d-none d-md-block" title="Смотреть подробней"
                    data-toggle="modal"
                    data-target="#productModalDetails-{{ $product->id }}">
                <i class="svg-icon" data-feather="zoom-in"></i>
            </button>

            <div class="card-image">

                <a href="{{ $product->href }}">

                    @if($product->primaryImage)
                        <img class="img-fluid w-100" src="/storage/{{ $product->primaryImage->medium }}"
                             alt="Изображение {{ $product->name }}"/>
                    @else
                        <img class="img-fluid w-100" src="{{ url('/images/common/no_image.png') }}"
                             alt="Нет изображения продукта"/>
                    @endif

                </a>

            </div>

            <div class="card-body">

                <div class="card-title">
                    <a class="text-gray" href="{{ $product->href }}"
                       title="{{ $product->name }}">{{ $product->name }}</a>
                </div>

                @if($product->actualBadges->count())
                    <div class="d-flex justify-content-end mb-3">
                        @foreach($product->actualBadges as $badge)
                            <span class="badge badge-{{ $badge->class }} d-inline-block ml-1">{{ $badge->name }}</span>
                        @endforeach
                    </div>
                @endif

                @if($product->localPrice)
                    <div class="d-flex justify-content-around">
                        <div class="h5 px-1">{{ $product->localPrice }}&nbsp;грн</div>
                        @if($product->price)
                            <div class="text-gray-hover px-1">${{ $product->price }}</div>
                        @endif
                    </div>
                @endif

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
                        <i class="svg-icon text-info" data-feather="alert-circle"></i>
                        <span class="ml-2">Продукт под заказ</span>
                    @endif
                </div>

            </div>

            @if($product->cartAble)
                <div class="card-footer">
                    <a href="{{ route('shop.cart.add', ['id' => $product->id]) }}"
                       class="btn btn-sm rounded-pill btn-outline-primary btn-block add-to-cart">В корзину</a>
                </div>
            @endif

        </div>

        {{-- product details modal--}}
        @include('content.shop.category.leaf.grid.parts.details', ['product' => $product])

    </div>

@endforeach

