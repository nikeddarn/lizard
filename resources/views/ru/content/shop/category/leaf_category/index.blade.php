@extends('layouts.shop')

@section('content')

    <div id="category-products-list" class="row mb-3">

        @if($filters->count())
            <div class="col-md-4 col-lg-3">
                @include('content.shop.category.leaf_category.parts.filters')
            </div>
        @endif

        <div class="col-md-8 col-lg-9">

            <div class="shop-section-wrapper my-4">

                <div class="row align-items-center">
                    <div class="col">
                        @include('layouts.parts.breadcrumbs.shop.index')
                    </div>
                    <div class="col-auto">
                        <a href="" class="btn btn-icon rounded-pill btn-sm btn-primary ml-3">

                        </a>
                        <a href="" class="btn btn-icon rounded-pill btn-sm btn-primary ml-1">

                        </a>
                    </div>

                </div>

                {{--<div class="col-lg-12 mt-2 mb-4 my-md-4">--}}
                    {{--<div class="underlined-title">--}}
                        {{--<h1 class="h4 text-gray-lighter px-2">{{ $category->name }}</h1>--}}
                    {{--</div>--}}
                {{--</div>--}}

            </div>

            <div class="row">
                @include('content.shop.category.leaf_category.parts.products')
            </div>

            <div class="row">
                @if($products->links())
                    <div class="col-lg-12 my-4">{{$products->links()}}</div>
                @endif
            </div>

        </div>

    </div>

    <div class="row">
            <div class="col-lg-12 my-5">{!! $category->content !!}</div>
    </div>

    @include('content.shop.category.leaf_category.parts.modal_product_favourite_added')

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
                        } else {
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

        });

    </script>
@endsection
