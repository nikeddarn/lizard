<h1 class="text-gray-hover h3">{{ $product->name }}</h1>

@if($product->localPrice)
    <h2 class="product-price my-4">{{ $product->localPrice }}&nbsp; грн
        @if($product->price)
            <small class="ml-4 text-gray-hover">$&nbsp;{{ $product->price }}</small>
        @endif
    </h2>
@endif

@if($product->is_archive)
    <h2 class="text-danger bold my-4">Архівний</h2>
@endif

<form id="add-to-cart" method="post" action="{{ route('shop.cart.count') }}">
    @csrf
    <input type="hidden" name="product_id[]" value="{{ $product->id }}">
    <input type="hidden" name="locale" value="uk">

    <table class="table product-details text-gray-hover">
        <tbody>

        @if($product->brief_content)
            <tr>
                <td colspan="2">{!! $product->brief_content !!}</td>
            </tr>
        @endif

        @if($product->model)
            <tr>
                <td>Модель товару</td>
                <td>{{ $product->model }}</td>
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
                        <span class="ml-2 text-lizard">{{ $product->productRateCount }}</span>
                    </div>
                </td>
            </tr>
        @endif

        @if(isset($product->defectRate))
            <tr>
                <td>Гарантійних повернень</td>
                <td>
                    <span>{{ $product->defectRate }}&nbsp;%</span>
                    <span class="ml-2 text-lizard">{{ $product->defectRateCount }}</span>
                </td>
            </tr>
        @endif

        <tr>
            <td>Доступність</td>
            <td>
                @if($product->availableProductStorages->count())
                    <div>
                        @foreach($product->availableProductStorages as $storage)
                            <span
                                class="badge badge-success font-weight-normal h6 px-2 my-2 mr-2">{{ $storage->city->name_uk }}
                                                            :&emsp;{{ $storage->name_uk }}</span>
                        @endforeach
                    </div>
                @elseif(!empty($product->isAvailable))
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
            </td>
        </tr>

        @if($product->manufacturer)
            <tr>
                <td>Виробництво</td>
                <td>{{ $product->manufacturer }}</td>
            </tr>
        @endif

        <tr>
            <td>Стан</td>
            <td>
                @if($product->is_new)
                    <span>Новий</span>
                @else
                    <span>Знижений у ціні</span>
                @endif
            </td>
        </tr>

        @if(isset($product->warranty))
            <tr>
                <td>Гарантія</td>
                <td>{{ $product->warranty }} міс.</td>
            </tr>
        @endif

        @if($product->productFiles->count())
            <tr>
                <td colspan="2">
                    @foreach($product->productFiles as $productFile)
                        <a href="{{ '/storage/' . $productFile->url }}" class="h6 text-lizard mr-5" title="Завантажити">
                            <i class="svg-icon-larger" data-feather="file"></i>
                            <span>{{ $productFile->name_uk }}</span>
                        </a>
                    @endforeach
                </td>
            </tr>
        @endif

        </tbody>
    </table>

    @if($product->cartAble)
        <div class="row d-sm-flex align-items-end justify-content-around my-5">

            <div class="col-12 col-sm-auto">
                <input id="productQuantity" class="d-inline-block" name="count[]" required
                       value="{{ old('quantity', 1) }}" min="1">
            </div>

            <div class="col-12 col-sm-auto mt-4">
                <div id="product-details-actions" class="btn-group" role="group">
                    <button type="submit" class="btn btn-primary">Додати в кошик</button>

                    <a href="{{ route('user.favourites.remove', ['id' => $product->id]) }}"
                       class="btn product-favourite product-favourite-remove align-items-center justify-content-center{{ $product->isFavourite ?  ' active d-flex' : ' d-none'}}"
                       title="Видалити з обраного" data-add-title="Видалити з обраного"
                       data-remove-title="Видалити з обраного">
                        <i class="svg-icon-larger" data-feather="heart"></i>
                    </a>
                    <a href="{{ route('user.favourites.add', ['id' => $product->id]) }}"
                       class="btn product-favourite product-favourite-add align-items-center justify-content-center{{ $product->isFavourite ?  ' d-none' : ' d-flex'}}"
                       title="Додати в обране" data-remove-title="Додати в обране"
                       data-add-title="Додати в обране">
                        <i class="svg-icon-larger" data-feather="heart"></i>
                    </a>
                </div>
            </div>

        </div>

    @endif

</form>
