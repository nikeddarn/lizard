@extends('layouts.shop')

@section('content')

    <div class="row my-4">

        @if($filters->count())
            <div class="col-md-4 col-lg-3 d-none d-md-block">
                @include('content.shop.category.leaf.grid.parts.filters')
            </div>
        @endif

        <div class="{{ $filters->count() ? 'col-md-8 col-lg-9' : 'col-12' }}">

            <div class="card p-2 mb-4 d-lg-none">
                <h1 class="h5 text-gray-hover m-0">{{ $categoryName }}</h1>
            </div>

            <div class="card p-2 mb-4">

                <div class="row align-items-center">

                    <div class="col-auto d-block d-md-none mr-auto">
                        @if(isset($usedFilters) && $usedFilters->count())
                            <button id="modal-filter-toggle" class="btn btn-sm btn-outline-theme rounded-pill"
                                    data-toggle="modal"
                                    data-target="#filterModal">
                                <i class="svg-icon" data-feather="filter"></i>
                                <span class="pr-1">фильтры</span>
                                <span class="badge badge-danger">{{ $usedFilters->count() }}</span>
                            </button>
                        @else
                            <button id="modal-filter-toggle" class="btn btn-sm btn-outline-theme rounded-pill"
                                    data-toggle="modal"
                                    data-target="#filterModal">
                                <i class="svg-icon" data-feather="filter"></i>
                                <span>Фильтровать</span>
                            </button>
                        @endif
                    </div>

                        <div class="col d-none d-lg-block">
                            <h1 class="h5 text-gray-hover m-0">{{ $categoryName }}</h1>
                        </div>

                        <div class="col-auto col-md-12 col-lg-auto ml-auto">
                            @include('content.shop.category.leaf.grid.parts.product-control')
                        </div>

                </div>

            </div>

            <div class="row">
                @include('content.shop.category.leaf.grid.parts.products')
            </div>


            @if($products->lastPage() !== 1)
                <div class="row">
                    <div class="col-12 my-4">
                        @include('layouts.parts.pagination.products.index', ['paginator' => $products])
                    </div>
                </div>
            @endif

            @if($products->currentPage() === 1 && !empty($categoryContent))
                <div class="row">
                    <div class="col-lg-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                {!! $categoryContent !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

    </div>

    {{-- product filters modal--}}
    @include('content.shop.category.leaf.grid.parts.filters_modal')

@endsection

@section('styles')
    <!-- bootstrap-touchspin -->
    <link href="{{ url('/css/jquery.bootstrap-touchspin.css') }} " rel="stylesheet">
@endsection

@section('scripts')
    <!-- bootstrap-touchspin -->
    <script src="{{ url('/js/jquery.bootstrap-touchspin.js') }}"></script>

    <script>

        $(document).ready(function () {

            // activate Touch Spin
            $(".touchspin").TouchSpin({
                min: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

        });

    </script>
@endsection
