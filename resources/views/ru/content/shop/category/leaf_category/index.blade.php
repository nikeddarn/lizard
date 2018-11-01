@extends('layouts.shop')

@section('content')

    <div id="category-products-list" class="row mb-3">

        @if($filters->count())
            <div class="col-md-4 col-lg-3">
                @include('content.shop.category.leaf_category.parts.filters')
            </div>
        @endif

        <div class="col-md-8 col-lg-9">

            <div class="row">

                <div class="col-lg-12 mb-4 my-md-4">
                    <div class="underlined-title">
                        <h1 class="h4 text-gray-lighter px-2">{{ $category->name }}</h1>
                    </div>
                </div>

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
    <!-- bootstrap-touchspin -->
    <link href="/css/jquery.bootstrap-touchspin.css" rel="stylesheet">
@endsection

@section('scripts')
    <!-- bootstrap-touchspin -->
    <script src="/js/jquery.bootstrap-touchspin.js"></script>

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