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
                <div class="col-sm-8 mt-2 mt-sm-4 product-wrapper">
                    @include('content.shop.product.details.parts.details')
                </div>

                {{--tabs--}}
                <div class="col-12 mt-2 mt-sm-4">
                    @include('content.shop.product.details.parts.tabs')
                </div>

                {{--Videos--}}
                @if($product->productVideos->count())
                    <div class="col-12 mt-2 mt-sm-4">
                        @include('content.shop.product.details.parts.videos')
                    </div>
                @endif

            </div>

        </div>
    </div>

    {{-- cart modal--}}
    @include('elements.cart.cart_modal')

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

            // add to cart
            $('#add-to-cart').submit(function (event) {

                event.preventDefault();
                event.stopImmediatePropagation();

                $.post({
                    url: this.action,
                    data: $(this).serialize(),
                    success: function (data) {
                        let cartData = JSON.parse(data);
                        // set header cart data
                        $('#cart-items-count').html(cartData.itemsCount);
                        $('#header-cart-content').removeClass('d-none').html(cartData.cart);
                        // set modal cart data and activate modal
                        let cartModal = $('#cart-content-modal');
                        $(cartModal).find('.modal-cart').html(cartData.cart);
                        $(cartModal).modal();
                    }
                });
            });

        });

    </script>

@endsection
