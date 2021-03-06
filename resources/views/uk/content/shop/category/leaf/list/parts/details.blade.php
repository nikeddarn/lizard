<div class="modal fade" id="productModalDetails-{{ $product->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title h3 text-center">
                    <a href="{{ $product->href }}"
                       class="text-dark">{{ $product->name }}</a>
                </h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-0">
                <div class="container-fluid">
                    <div class="row">

                        @if($product->productImages->count())

                            <div class="col col-sm-6">

                                <div id="product-detail-carousel-{{ $product->id }}" class="carousel slide"
                                     data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        @foreach($product->productImages as $key => $image)
                                            @if ($loop->first)
                                                <li data-target="#product-detail-carousel-{{ $product->id }}"
                                                    data-slide-to="{{ $key }}"
                                                    class="active"></li>
                                            @else
                                                <li data-target="#product-detail-carousel-{{ $product->id }}"
                                                    data-slide-to="{{ $key }}"></li>
                                            @endif
                                        @endforeach
                                    </ol>
                                    <div class="carousel-inner">
                                        @foreach($product->productImages as $key => $image)
                                            @if ($loop->first)
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100" src="/storage/{{ $image->medium }}"
                                                         alt="product image">
                                                </div>
                                            @else
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="/storage/{{ $image->medium }}"
                                                         alt="product image">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <a class="carousel-control-prev" href="#product-detail-carousel-{{ $product->id }}"
                                       role="button"
                                       data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Попереднє</span>
                                    </a>
                                    <a class="carousel-control-next" href="#product-detail-carousel-{{ $product->id }}"
                                       role="button"
                                       data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Наступне</span>
                                    </a>
                                </div>

                            </div>

                        @endif


                        <div class="col col-sm-6">

                            @if($product->localPrice)
                                <h2 class="product-price my-2">{{ $product->localPrice }}&nbsp; грн
                                    @if($product->price)
                                        <small class="ml-4 text-gray-hover">$&nbsp;{{ $product->price }}</small>
                                    @endif
                                </h2>
                            @endif

                            <form action="{{ route('shop.cart.count', ['id' => $product->id]) }}" method="post">
                                @csrf
                                <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                <input type="hidden" name="locale" value="uk">

                                <table class="table table-border-top-none product-details">
                                    <tbody>

                                    @if($product->brief_content)
                                        <tr>
                                            <td colspan="2">{!! $product->brief_content !!}</td>
                                        </tr>
                                    @endif

                                    @if($product->model_uk)
                                        <tr>
                                            <td>Модель</td>
                                            <td>{{ $product->model_uk }}</td>
                                        </tr>
                                    @endif

                                    @if(isset($product->defectRate))
                                        <tr>
                                            <td>Гарантійних повернень</td>
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

                                    @if($product->manufacturer_uk)
                                        <tr>
                                            <td>Виробництво</td>
                                            <td>{{ $product->manufacturer_uk }}</td>
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

                                    @if($product->cartAble)
                                        <tr>
                                            <td>
                                                <label class="m-0"
                                                       for="productQuantity-{{ $product->id }}">Кількість</label>
                                            </td>
                                            <td>
                                                <input id="productQuantity-{{ $product->id }}" class="touchspin"
                                                       name="count[]" type="number" required
                                                       value="{{ old('count', 1) }}"
                                                       min="1">
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td></td>
                                        <td>
                                            <div id="product-details-actions" class="btn-group d-inline-flex"
                                                 role="group">
                                                @if($product->cartAble)
                                                    <button type="submit" class="btn btn-primary">Додати в кошик
                                                    </button>
                                                @endif
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
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
