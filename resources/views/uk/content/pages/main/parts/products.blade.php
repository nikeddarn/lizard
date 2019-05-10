@foreach($productGroups as $group)

    <div class="products-group">
        <div class="bg-white">
            <h1 class="h5  p-4 m-0">{{ $group->name }}</h1>
            <div class="row no-gutters mx-0">

                @foreach($group->products as $product)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ $product->href }}">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="card-image">
                                        <img class="img-fluid w-100"
                                             src="/storage/{{ $product->primaryImage->medium }}"
                                             alt="Зображення {{ $product->name_uk }}"/>
                                    </div>
                                    <h2 class="h6 text-gray-hover text-center my-2">{{ $product->name_uk }}</h2>
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
        <div class="text-right show-all-products-button">
            <div class="card card-body d-inline-block show-all-products-card p-0">
                <button class="btn btn-link text-lizard text-decoration-none">
                    <span>Дивитися ще</span>
                    <i class="svg-icon" data-feather="chevrons-down"></i>
                </button>
            </div>
        </div>
    </div>

@endforeach
