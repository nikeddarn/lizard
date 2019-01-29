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

                            <div class="col-4 col-lg-6">

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
                                        <span class="sr-only">Предыдущее</span>
                                    </a>
                                    <a class="carousel-control-next" href="#product-detail-carousel-{{ $product->id }}"
                                       role="button"
                                       data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Следующее</span>
                                    </a>
                                </div>

                            </div>

                        @endif


                        <div class="col-8 col-lg-6">

                            @if($product->localPrice)
                                <h2 class="product-price my-2">{{ $product->localPrice }}&nbsp; грн
                                    @if($product->price)
                                        <small class="ml-4 text-gray-hover">$&nbsp;{{ $product->price }}</small>
                                    @endif
                                </h2>
                            @endif

                            <form action="{{ route('shop.cart.count', ['id' => $product->id]) }}" method="post">

                                @csrf

                                <table class="table table-responsive table-border-top-none product-details">
                                    <tbody>

                                    @if($product->brief_content)
                                        <tr>
                                            <td colspan="2">{!! $product->brief_content !!}</td>
                                        </tr>
                                    @endif

                                    @if($product->model_ru)
                                        <tr>
                                            <td>Модель</td>
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
                                        <td>
                                            <label class="m-0"
                                                   for="productQuantity-{{ $product->id }}">Количество</label>
                                        </td>
                                        <td>
                                            <input id="productQuantity-{{ $product->id }}" class="touchspin"
                                                   name="quantity" type="number" required
                                                   value="{{ old('quantity', 1) }}"
                                                   min="1">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="btn-group mt-4" role="group" aria-label="Basic example">
                                                <a href="{{ route('shop.cart.add', ['id' => $product->id]) }}"
                                                   class="btn btn-primary">Добавить в корзину</a>
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

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>