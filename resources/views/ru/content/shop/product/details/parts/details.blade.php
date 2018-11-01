<h1 class="text-gray h3">{{ $product->name }}</h1>

<form action="{{ route('shop.cart.count', ['id' => $product->id]) }}" method="post">

    @csrf

    <table id="product-detail-table" class="table">
        <tbody>

        <tr>
            <td>Артикул товара</td>
            <td>{{ $product->id }}</td>
        </tr>

        @if($product->price)
            <tr>
                <td>Цена</td>
                <td class="product-price">
                    @if($product->localPrice)
                        <span>{{ $product->localPrice }}&nbsp; грн</span>
                    @endif
                    <span class="ml-2 ml-md-4 ml-lg-5">$&nbsp;{{ $product->price }}</span>
                </td>
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
                @if(isset($product->productAvailableStorages) && $product->productAvailableStorages->count())
                    <div>
                        @foreach($product->productAvailableStorages as $storage)
                            <span class="badge badge-success font-weight-normal py-1 px-2 storage-badge my-2 mr-2">{{ $storage->city->name }}
                                :&emsp;{{ $storage->name }}</span>
                        @endforeach
                    </div>
                @elseif(isset($product->availableTime))
                    @if(is_object($product->availableTime))
                        <span class="label label-success">Ожидается {{ $product->availableTime->diffForHumans() }}</span>
                    @else
                        <span class="label label-success">Ожидается в ближайшее время</span>
                    @endif
                @else
                    <span class="label label-warning">Товара нет в наличии</span>
                @endif
            </td>
        </tr>

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