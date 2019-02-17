@extends('layouts.product')

@section('content')

    <div class="card my-2 my-sm-4">
        <div class="card-body">

            <div class="row">

                {{--images--}}
                <div class="col-sm-4 mt-2 mt-sm-4">
                    @include('content.shop.product.details.parts.images')
                </div>

                {{--details--}}
                <div class="col-sm-8 mt-2 mt-sm-4">
                    @include('content.shop.product.details.parts.details')
                </div>

                {{--tabs--}}
                <div class="col-12 mt-2 mt-sm-4">
                    @include('content.shop.product.details.parts.tabs')
                </div>

            </div>

        </div>
    </div>

    @include('content.shop.product.details.parts.modal_product_favourite_added')

@endsection

@section('breadcrumbs')

    <div class="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-2">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    @foreach($breadcrumbs as $name => $href)
                        @if ($loop->last)
                            <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
                        @else
                            <li class="breadcrumb-item">
                                <a href="{{ $href }}">{{ $name }}</a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>

@endsection

@section('styles')
    {{-- product images carousel styles --}}
    <link rel="stylesheet" href="{{ url('/css/owl.carousel.min.css') }}">

    {{-- product main image zoom styles --}}
    <link rel="stylesheet" href="{{ url('/css/owl.theme.default.min.css') }}">

    <!-- bootstrap-touchspin -->
    <link rel="stylesheet" href="{{ url('/css/jquery.bootstrap-touchspin.css') }}">
@endsection

@section('scripts')

    {{-- product main image zoom--}}
    <script src="{{ url('/js/jquery.ez-plus.js') }}"></script>

    {{-- product images carousel--}}
    <script src="{{ url('/js/owl.carousel.min.js') }}"></script>

    <!-- bootstrap-touchspin -->
    <script src="{{ url('/js/jquery.bootstrap-touchspin.js') }}"></script>

    {{-- input rating field creator--}}
    <script src="{{ url('/js/bootstrap-rating.min.js') }}"></script>

    <script>

        $(document).ready(function () {

            // activate Touch Spin
            $("#productQuantity").TouchSpin({
                min: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

            // product main image zoom
            let zoomImage = $('#zoom-image');
            let activateZoom = function activateZoom(zoomImage) {
                if (!('ontouchstart' in window)) {
                    zoomImage.ezPlus({
                        zoomWindowOffsetX: 30,
                        zoomWindowWidth: $('#product-description').width(),
                        zoomWindowHeight: zoomImage.height(),
                    });
                }
            };

            activateZoom(zoomImage);

            // change main image
            $('.owl-carousel img').click(function () {
                let newImageSrc = $(this).attr('src');
                activateZoom(zoomImage.attr('src', newImageSrc).data('zoom-image', newImageSrc));
            });

            // product images carousel
            $('.owl-carousel').owlCarousel({
                dots: false,
                nav: true,
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                margin: 5,
                responsive: {
                    0: {
                        items: 2,
                    },
                    768: {
                        items: 3,
                    },
                    1200: {
                        items: 4,
                    }
                }
            });
        });

    </script>

@endsection
