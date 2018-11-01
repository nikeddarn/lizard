@extends('layouts.shop')

@section('content')

    <div class="row page-content mb-4">

        {{--images--}}
        <div class="col-sm-4">
            @include('content.shop.product.details.parts.images')
        </div>

        <div class="col-sm-8">
            {{--details--}}
            @include('content.shop.product.details.parts.details')
            {{--tabs--}}
            @include('content.shop.product.details.parts.tabs')
        </div>

    </div>

    @include('content.shop.product.details.parts.modal_product_favourite_added')

@endsection

@section('styles')
    {{-- product images carousel styles --}}
    <link rel="stylesheet" href="/css/owl.carousel.min.css">

    {{-- product main image zoom styles --}}
    <link rel="stylesheet" href="/css/owl.theme.default.min.css">

    <!-- bootstrap-touchspin -->
    <link href="/css/jquery.bootstrap-touchspin.css" rel="stylesheet">
@endsection

@section('scripts')

    {{-- product main image zoom--}}
    <script src="/js/jquery.ez-plus.js"></script>

    {{-- product images carousel--}}
    <script src="/js/owl.carousel.min.js"></script>

    <!-- bootstrap-touchspin -->
    <script src="/js/jquery.bootstrap-touchspin.js"></script>

    {{-- input rating field creator--}}
    <script src="/public/js/bootstrap-rating.min.js"></script>

    <script>

        $(document).ready(function () {

            // activate Touch Spin
            $("#productQuantity").TouchSpin({
                min: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

            // add to favourite
            $('.product-favourite-add').click(function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                $.get(this, null, function (data) {

                    // increase header badge's count
                    if (data === '1') {
                        let badgeLink = $('#header-favourite-products');
                        let badge = $(badgeLink).find('span');
                        if (badge.length) {
                            $(badge).text(parseInt($(badge).text()) + 1);
                        }else {
                            $(badgeLink).prepend($('<span>1</span>'));
                        }
                    }

                    // activate modal
                    let modal = $('#modal-product-favourite-added');
                    $(modal).modal('show');
                    setTimeout(function () {
                        $(modal).modal('hide');
                    }, 3000);
                })
            });

            // product main image zoom
            let zoomImage = $('#zoom-image');
            let activateZoom = function activateZoom(zoomImage) {
                zoomImage.ezPlus({
                    zoomWindowOffsetX: 30,
                    zoomWindowWidth: $('#product-description').width(),
                    zoomWindowHeight: zoomImage.height(),
                });
            };

            activateZoom(zoomImage);

            // change main image
            $('.owl-carousel img').click(function (event) {
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