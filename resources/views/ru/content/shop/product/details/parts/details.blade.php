<h1 class="text-gray h3">{{ $product->name }}</h1>

@if($product->price)
    @if($product->localPrice)
        <h2 class="product-price my-4">{{ $product->localPrice }}&nbsp; грн<small class="ml-4 text-gray-lighter">$&nbsp;{{ $product->price }}</small></h2>
    @else
        <h2 class="product-price my-4">$&nbsp;{{ $product->price }}</h2>
    @endif
@endif

<form action="{{ route('shop.cart.count', ['id' => $product->id]) }}" method="post">

    @csrf

    <table class="table product-details">
        <tbody>

        @if($product->brief_content)
            <tr>
                <td colspan="2">{!! $product->brief_content !!}</td>
            </tr>
        @endif

        @if($product->model_ru)
            <tr>
                <td>Модель товара</td>
                <td>{{ $product->model_ru }}</td>
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
                            <span class="badge badge-success font-weight-normal h6 px-2 my-2 mr-2">{{ $storage->city->name_ru }}
                                                            :&emsp;{{ $storage->name_ru }}</span>
                        @endforeach
                    </div>
                @elseif($product->isAvailable)
                    <div class="available-product">Готов к отгрузке</div>
                @elseif($product->isExpectedToday)
                    <div class="available-product">Ожидается сегодня</div>
                @elseif($product->expectedAt)
                    <div class="expected-product">Ожидается {{ $product->expectedAt->diffForHumans() }}</div>
                @else
                    <div class="unavailable-product">Нет в наличии</div>
                @endif
            </td>
        </tr>

        @if($product->manufacturer_ru)
            <tr>
                <td>Производство</td>
                <td>{{ $product->manufacturer_ru }}</td>
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

        <tr>
            <td><label class="m-0" for="productQuantity">Количество</label></td>
            <td>
                <input id="productQuantity" name="quantity" type="number" required value="{{ old('quantity', 1) }}"
                       min="1">
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <div class="btn-group mt-4" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary">Добавить в корзину</button>
                    <a href="{{ route('user.favourites.add', ['id' => $product->id]) }}"
                       class="btn btn-outline-theme product-favourite-add"
                       data-toggle="tooltip" data-placement="top" title="В избранное">
                        <i class="fa fa-heart-o"></i>
                    </a>
                </div>
            </td>
        </tr>

        </tbody>
    </table>

</form>
