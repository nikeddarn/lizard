<table class="table table-cart table-wishlist">

    <tbody>

    @foreach($recentProducts as $product)

        <tr>

            <td>

                <div class="media">

                    <a href="{{ $product->href }}" class="mr-3 d-none d-md-block">
                        @if($product->primaryImage)
                            <img class="img-fluid table-product-image"
                                 src="{{ url('/storage/' . $product->primaryImage->small) }}"
                                 alt="Изображение продукта">
                        @else
                            <img class="img-fluid table-product-image"
                                 src="{{ url('/images/common/no_image.png') }}" alt="Нет изображения продукта"/>
                        @endif
                    </a>

                    <div class="media-body align-self-center">
                        <a href="{{ $product->href }}" class="h6">{{ $product->name }}</a>
                        <div class="mb-1">

                            @if($product->localPrice)
                                <span class="d-inline d-sm-none">Цена:
                                            <span class="product-price">{{ $product->localPrice }}&nbsp;грн</span>
                                        @if($product->price)
                                        <span class="ml-1">${{ $product->price }}</span>
                                    @endif
                                    </span>
                            @endif

                            @if($product->isAvailable)
                                <div class="text-gray">
                                    <i class="svg-icon text-success" data-feather="check-circle"></i>
                                    <span class="ml-2">Готов к отгрузке</span>
                                </div>
                            @elseif($product->isExpectedToday)
                                <div class="text-gray">
                                    <i class="svg-icon text-warning" data-feather="clock"></i>
                                    <span class="ml-2">Ожидается сегодня</span>
                                </div>
                            @elseif($product->expectedAt)
                                <div class="text-gray">
                                    <i class="svg-icon text-warning" data-feather="clock"></i>
                                    <span class="ml-2">Ожидается {{ $product->expectedAt->diffForHumans() }}</span>
                                </div>
                            @else
                                <div class="text-gray">
                                    <i class="svg-icon text-danger" data-feather="alert-circle"></i>
                                    <span class="ml-2">Нет в наличии</span>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>

            </td>

            <td class="d-none d-sm-table-cell">
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
            </td>

            <td class="text-center">
                <div class="btn-group" role="group" aria-label="Favourite action">
                    <a href="{{ route('shop.cart.add', ['id' => $product->id]) }}"
                       class="btn btn-primary btn-sm">КУПИТЬ</a>
                    <a href="{{ route('user.favourites.remove', ['id' => $product->id]) }}"
                       class="btn btn-sm btn-outline-theme">
                        <i class="fa fa-close"></i>
                    </a>
                </div>
            </td>
        </tr>

    @endforeach

    </tbody>
</table>
