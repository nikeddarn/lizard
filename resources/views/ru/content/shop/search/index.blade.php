@extends('layouts.common')

@section('content')

    <div class="container">

        <div class="card card-body p-4 my-4">

            <h1 class="h5 text-center text-gray-hover mb-5">
                <span>Результаты поиска по запросу:</span>
                <span class="ml-2 ml-lg-4">{{ $searchingText }}</span>
            </h1>

            @if($products->count())
                <div class="row">
                    @include('content.shop.search.parts.products')
                </div>
            @else
                <p class="h5 text-gray-hover">Ничего не найдено</p>
            @endif

        </div>

        @if($products->count() && $products->lastPage() !== 1)
            <div class="row">
                <div class="col-12 my-4">
                    @include('layouts.parts.pagination.products.index', ['paginator' => $products])
                </div>
            </div>
        @endif

    </div>

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
