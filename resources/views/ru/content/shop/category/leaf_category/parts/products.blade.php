@foreach($products as $product)

    <div class="col-6 col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-4">

        <div class="h-100 card card-product">

            <div class="card-image">

                <a href="{{ route('shop.product.index', ['url' => $product->url]) }}">

                    @if($product->primaryImage)
                        <img class="img-fluid w-100" src="/storage/{{ $product->primaryImage->medium }}" alt="Изображение {{ $product->name }}"/>
                    @else
                        <img class="img-fluid w-100" src="{{ url('/images/common/no_image.png') }}"  alt="Нет изображения продукта"/>
                    @endif

                    @if($product->badges)
                        <div class="product-badges">
                            @foreach($product->badges as $badge)
                                <span class="badge-label">
                                <span
                                    class="label label-arrow label-arrow-left label-{{ $badge['class'] }}">{{ $badge['title'] }}</span>
                            </span>
                            @endforeach
                        </div>
                    @endif

                </a>

                @if($product->actualBadges->count())
                    <div class="product-card-badges">
                        @foreach($product->actualBadges as $badge)
                            <span class="badge badge-{{ $badge->class }} d-block mb-2">{{ $badge->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="action">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Action">
                        <button class="btn btn-outline-theme" title="Смотреть подробней" data-toggle="modal"
                                data-target="#productModalDetails-{{ $product->id }}">
                            <i class="fa fa-search-plus"></i>
                        </button>
                        <a href="{{ route('shop.cart.add', ['id' => $product->id]) }}" class="btn btn-theme">В
                            корзину</a>
                        <a href="{{ route('user.favourites.add', ['id' => $product->id]) }}"
                           class="btn btn-outline-theme product-favourite-add" type="submit" title="В избранное">
                            <i class="fa fa-heart-o"></i>
                        </a>
                    </div>
                </div>

            </div>

            <div class="card-body">

                <div class="card-title">
                    <a class="text-gray" href="{{ route('shop.product.index', ['url' => $product->url]) }}"
                       title="{{ $product->name }}">{{ $product->name }}</a>
                </div>

                @if($product->price)
                    <div class="row no-gutters justify-content-between mb-2">
                        @if($product->localPrice)
                            <div class="col-auto h5 px-1">{{ $product->localPrice }}&nbsp;грн</div>
                        @endif
                        <div class="col-auto text-gray px-1">${{ $product->price }}</div>
                    </div>
                @endif

                @if($product->isAvailable)
                    <div class="available-product">Готов к отгрузке</div>
                @elseif($product->isExpectedToday)
                    <div class="available-product">Ожидается сегодня</div>
                @elseif($product->expectedAt)
                    <div class="expected-product">Ожидается {{ $product->expectedAt->diffForHumans() }}</div>
                @else
                    <div class="unavailable-product">Нет в наличии</div>
                @endif

                <div class="small-action d-inline-block d-md-none">
                    <div class="btn-group dropup">
                        <span role="button" data-toggle="dropdown" aria-haspopup="true"
                              aria-expanded="false">&#10247;</span>
                        <div class="dropdown-menu dropdown-menu-right fadeIn">
                            <a class="dropdown-item" href="{{ route('shop.cart.add', ['id' => $product->id]) }}"><i
                                    class="fa fa-cart-arrow-down"></i>&nbsp;Купить</a>
                            <a class="dropdown-item product-favourite-add"
                               href="{{ route('user.favourites.add', ['id' => $product->id]) }}">
                                <i class="fa fa-heart"></i>&nbsp;В избранное</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @include('content.shop.category.leaf_category.parts.details', ['product' => $product])

@endforeach

