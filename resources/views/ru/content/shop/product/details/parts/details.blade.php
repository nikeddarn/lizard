<h1 class="text-gray-hover h3">{{ $product->name }}</h1>

@if($product->localPrice)
    <h2 class="product-price my-4">{{ $product->localPrice }}&nbsp; грн
        @if($product->price)
            <small class="ml-4 text-gray-hover">$&nbsp;{{ $product->price }}</small>
        @endif
    </h2>
@endif

@if($product->is_archive)
    <h2 class="text-danger bold my-4">Архивный</h2>
@endif

<form id="add-to-cart" method="post" action="{{ route('shop.cart.count') }}">
    @csrf
    <input type="hidden" name="product_id[]" value="{{ $product->id }}">

    <table class="table product-details text-gray-hover">
        <tbody>

        @if($product->brief_content)
            <tr>
                <td colspan="2">{!! $product->brief_content !!}</td>
            </tr>
        @endif

        @if($product->model)
            <tr>
                <td>Модель товара</td>
                <td>{{ $product->model }}</td>
            </tr>
        @endif

        @if(isset($product->defectRate))
            <tr>
                <td>Гарантийных возвратов</td>
                <td>{{ $product->defectRate }}&nbsp;%</td>
            </tr>
        @endif

        @if(isset($product->productRate))
            <tr>
                <td>Рейтинг</td>
                <td>
                    <div class="product-rating">
                        @for($i=1; $i<=5; $i++)
                            @if($product->rating >= $i)
                                <span class="fa fa-star" aria-hidden="true"></span>
                            @else
                                <span class="fa fa-star-o" aria-hidden="true"></span>
                            @endif
                        @endfor
                    </div>
                </td>
            </tr>
        @endif

        <tr>
            <td>Доступность</td>
            <td>
                @if($product->availableProductStorages->count())
                    <div>
                        @foreach($product->availableProductStorages as $storage)
                            <span
                                class="badge badge-success font-weight-normal h6 px-2 my-2 mr-2">{{ $storage->city->name_ru }}
                                                            :&emsp;{{ $storage->name_ru }}</span>
                        @endforeach
                    </div>
                @elseif(!empty($product->isAvailable))
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
            </td>
        </tr>

        @if($product->manufacturer)
            <tr>
                <td>Производство</td>
                <td>{{ $product->manufacturer }}</td>
            </tr>
        @endif

        <tr>
            <td>Состояние</td>
            <td>
                @if($product->is_new)
                    <span>Новый</span>
                @else
                    <span>Уцененный</span>
                @endif
            </td>
        </tr>

        @if(isset($product->warranty))
            <tr>
                <td>Гарантия</td>
                <td>{{ $product->warranty }} мес.</td>
            </tr>
        @endif

        </tbody>
    </table>

    @if($product->cartAble)
        <div class="row d-sm-flex align-items-end justify-content-around my-4">

            <div class="col-12 col-sm-auto">
                <input id="productQuantity" class="d-inline-block" name="count[]" required
                       value="{{ old('quantity', 1) }}" min="1">
            </div>

            <div class="col-12 col-sm-auto mt-4">
                <div id="product-details-actions" class="btn-group" role="group">
                    <button type="submit" class="btn btn-primary">Добавить в корзину</button>

                    <a href="{{ route('user.favourites.remove', ['id' => $product->id]) }}"
                       class="btn product-favourite product-favourite-remove align-items-center justify-content-center{{ $product->isFavourite ?  ' active d-flex' : ' d-none'}}"
                       title="Удалить из избранного" data-add-title="Удалить из избранного"
                       data-remove-title="Удалить из избранного">
                        <i class="svg-icon-larger" data-feather="heart"></i>
                    </a>
                    <a href="{{ route('user.favourites.add', ['id' => $product->id]) }}"
                       class="btn product-favourite product-favourite-add align-items-center justify-content-center{{ $product->isFavourite ?  ' d-none' : ' d-flex'}}"
                       title="Добавить в избранное" data-remove-title="Добавить в избранное"
                       data-add-title="Добавить в избранное">
                        <i class="svg-icon-larger" data-feather="heart"></i>
                    </a>
                </div>
            </div>

        </div>

    @endif

</form>
