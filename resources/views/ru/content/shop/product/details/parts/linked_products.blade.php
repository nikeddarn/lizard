<div class="container-fluid bg-white">

    <h2 class="h5 text-gray-hover px-4 pt-4">Вас также могут заинтересовать<span
            class="d-none d-lg-inline"> товары в категории {{ $category->name_ru }}</span></h2>

    <div id="relatedProductsCarousel" class="owl-carousel owl-theme">

        @foreach($linkedProducts as $product)
            <div class="owl-item">
                <a href="{{ $product->href }}">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-image">
                                @if($product->primaryImage)
                                    <img class="img-fluid w-100"
                                         src="/storage/{{ $product->primaryImage->medium }}"
                                         alt="Изображение {{ $product->name_ru }}"/>
                                @else
                                    <img src="{{ url('/images/common/no_image.png') }}">
                                @endif
                            </div>
                            <h2 class="h6 text-gray-hover text-center my-2">{{ $product->name_ru }}</h2>
                        </div>
                        @if($product->localPrice)
                            <div
                                class="card-footer bg-white border-0 h5 product-price text-center">{{ $product->localPrice }}
                                &nbsp;грн
                            </div>
                        @endif
                    </div>
                </a>
            </div>
        @endforeach

    </div>

</div>
