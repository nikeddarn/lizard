@foreach($products as $product)

    <div class="col-12 product-wrapper">

        <div class="card card-product card-product-list">

            <div class="card-image">

                <a href="{{ route('user.favourites.remove', ['id' => $product->id]) }}"
                   class="product-favourite product-favourite-remove align-items-center justify-content-center{{ $product->isFavourite ?  ' active d-flex' : ' d-none'}}"
                   title="Видалити з обраного" data-add-title="Видалити з обраного"
                   data-remove-title="Видалити з обраного">
                    <i class="svg-icon" data-feather="heart"></i>
                </a>
                <a href="{{ route('user.favourites.add', ['id' => $product->id]) }}"
                   class="product-favourite product-favourite-add align-items-center justify-content-center{{ $product->isFavourite ?  ' d-none' : ' d-flex'}}"
                   title="Додати в обране" data-remove-title="Додати в обране"
                   data-add-title="Додати в обране">
                    <i class="svg-icon" data-feather="heart"></i>
                </a>

                @if($product->productVideos->count())
                    <a href="{{ $product->href }}#product-video"
                       class="product-video-review btn btn-link d-none d-md-block"
                       title="Дивитися відеоогляд">
                        <i class="svg-icon" data-feather="video"></i>
                    </a>
                @endif

                <button class="product-quickview btn btn-link d-none d-md-block" title="Дивитися детальніше"
                        data-toggle="modal"
                        data-target="#productModalDetails-{{ $product->id }}">
                    <i class="svg-icon" data-feather="zoom-in"></i>
                </button>

                <a href="{{ $product->href }}">

                    @if($product->primaryImage)
                        <img class="img-fluid w-100" src="/storage/{{ $product->primaryImage->medium }}"
                             alt="Зображення {{ $product->name }}"/>
                    @else
                        <img class="img-fluid w-100" src="{{ url('/images/common/no_image.png') }}"
                             alt="Немає зображення продукту"/>
                    @endif

                </a>

            </div>

            <div class="card-body">

                <a class="card-title" href="{{ $product->href }}"
                   title="{{ $product->name }}">{{ $product->name }}</a>

                <div class="d-flex align-items-start mb-2">

                    @if($product->localPrice)
                        <div class="d-inline-block">
                            <span class="product-price h5">{{ $product->localPrice }}&nbsp;грн</span>
                            @if($product->price)
                                <span class="ml-2 h6">${{ $product->price }}</span>
                            @endif
                        </div>
                    @endif

                    @if($product->actualBadges->count())
                        <div class="ml-2 ml-md-4 d-inline-block">
                            @foreach($product->actualBadges as $badge)
                                <span class="badge badge-{{ $badge->class }}">{{ $badge->name }}</span>
                            @endforeach
                        </div>
                    @endif

                </div>

                <div class="d-none d-md-block mb-2 text-gray-hover">{!! $product->brief_content !!}</div>

                <div class="text-gray mb-4">
                    @if(!empty($product->isAvailable))
                        <i class="svg-icon text-success" data-feather="check-circle"></i>
                        <span class="ml-2">Готовий до відвантаження</span>
                    @elseif(!empty($product->isExpectedToday))
                        <i class="svg-icon text-warning" data-feather="clock"></i>
                        <span class="ml-2">Очікується сьогодні</span>
                    @elseif(!empty($product->isExpectedTomorrow))
                        <i class="svg-icon text-warning" data-feather="clock"></i>
                        <span class="ml-2">Очікується завтра</span>
                    @elseif(!empty($product->expectedAt))
                        <i class="svg-icon text-warning" data-feather="clock"></i>
                        <span class="ml-2">Очікується {{ $product->expectedAt->diffForHumans() }}</span>
                    @else
                        <i class="svg-icon text-info" data-feather="alert-circle"></i>
                        <span class="ml-2">Продукт під замовлення</span>
                    @endif
                </div>

                @if($product->cartAble)
                    <a href="{{ route('shop.cart.add', ['id' => $product->id]) }}"
                       class="btn btn-sm rounded-pill btn-outline-primary add-to-cart">Додати в кошик</a>
                @endif

            </div>
        </div>

        {{-- product details modal--}}
        <div class="d-none d-md-block">
            @include('content.shop.category.leaf.list.parts.details', ['product' => $product])
        </div>

    </div>

@endforeach

