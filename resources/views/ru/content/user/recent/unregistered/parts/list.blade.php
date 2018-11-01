@if($recentProducts->count())

    <table class="table table-cart table-wishlist">
        <thead>
        <tr>
            <th scope="col" class="w-50">Товар</th>
            <th scope="col" class="d-none d-sm-table-cell">Цена</th>
            <th scope="col" class="text-center">Действие</th>
        </tr>
        </thead>
        <tbody>

        @foreach($recentProducts as $product)

            <tr>

                <td>

                    <div class="media">

                        <a href="{{ route('shop.product.index', ['id' => $product->url]) }}"
                           class="mr-3 d-none d-md-block">
                            @if($product->primaryImage)
                                <img class="img-fluid table-product-image"
                                     src="/storage/{{ $product->primaryImage->image }}">
                            @else
                                <img class="img-fluid table-product-image" src="/images/common/no_image.png"/>
                            @endif
                        </a>

                        <div class="media-body">
                            <a href="{{ route('shop.product.index', ['id' => $product->url]) }}"
                               class="h6">{{ $product->name }}</a>
                            <div class="mb-1">

                                @if($product->price)
                                    <span class="d-inline d-sm-none">Цена:
                                        @if($product->localPrice)
                                            <span class="text-lizard">{{ $product->localPrice }}&nbsp;грн</span>
                                        @else
                                            <span class="text-lizard">${{ $product->price }}</span>
                                        @endif
                                    </span>
                                @endif

                                <div class="small mt-2 d-none d-sm-block">Наличие:</div>

                                @if(isset($product->isProductAvailable) && $product->isProductAvailable)
                                    <span class="badge badge-success custom-badge arrowed-right">На складе</span>
                                @elseif(isset($product->isProductExpected) && $product->isProductExpected)
                                    <span class="badge badge-secondary custom-badge arrowed-right">Ожидается</span>
                                @else
                                    <span class="badge badge-danger custom-badge arrowed-right">Нет в наличии</span>
                                @endif

                            </div>
                        </div>

                    </div>

                </td>

                <td class="d-none d-sm-table-cell">
                    @if($product->price)
                        <ul class="card-text list-inline">
                            @if($product->localPrice)
                                <li class="list-inline-item">
                                    <span class="text-lizard h5">{{ $product->localPrice }}&nbsp;грн</span>
                                </li>
                            @endif
                            <li class="list-inline-item">
                                <span>${{ $product->price }}</span>
                            </li>
                        </ul>
                    @endif
                </td>

                <td class="text-center">
                    <div class="btn-group" role="group" aria-label="Favourite action">
                        <a href="{{ route('shop.cart.add', ['id' => $product->id]) }}" class="btn btn-primary btn-sm">КУПИТЬ</a>
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

@endif