<h1 class="text-gray h3">{{ $product->name }}</h1>

@if($product->localPrice)
    <h2 class="product-price my-4">{{ $product->localPrice }}&nbsp; грн
        @if($product->price)
            <small class="ml-4 text-gray-hover">$&nbsp;{{ $product->price }}</small>
        @endif
    </h2>
@endif

<form action="{{ route('shop.cart.count', ['id' => $product->id]) }}" method="post">

    @csrf

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
                @elseif($product->isAvailable)
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
                        <span
                            class="ml-2">Ожидается {{ $product->expectedAt->diffForHumans() }}</span>
                    </div>
                @else
                    <div class="text-gray">
                        <i class="svg-icon text-danger" data-feather="alert-circle"></i>
                        <span class="ml-2">Нет в наличии</span>
                    </div>
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

    <div class="row d-sm-flex align-items-end justify-content-around my-4">

        <div class="col-12 col-sm-auto">
            <input id="productQuantity" class="d-inline-block" name="quantity" required value="{{ old('quantity', 1) }}"
                   min="1">
        </div>

        <div class="col-12 col-sm-auto mt-4">
            <div id="product-details-actions" class="btn-group d-flex" role="group">
                <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                <a href="{{ route('user.favourites.remove', ['id' => $product->id]) }}"
                   class="btn product-favourite product-favourite-remove align-items-center justify-content-center{{ $product->isFavourite ?  ' active d-flex' : ' d-none'}}"
                   title="Удалить из избранного" data-add-title="Удалить из избранного" data-remove-title="Удалить из избранного">
                    <i class="svg-icon-larger" data-feather="heart"></i>
                </a>
                <a href="{{ route('user.favourites.add', ['id' => $product->id]) }}"
                   class="btn product-favourite product-favourite-add align-items-center justify-content-center{{ $product->isFavourite ?  ' d-none' : ' d-flex'}}"
                   title="Добавить в избранное" data-remove-title="Добавить в избранное" data-add-title="Добавить в избранное">
                    <i class="svg-icon-larger" data-feather="heart"></i>
                </a>
            </div>
        </div>

    </div>

</form>
