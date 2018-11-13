@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">

        <div class="col admin-content-title">
            <h3 class="mb-2">Поставщик:<i class="ml-5 admin-content-sub-header">{{ $vendorCategory->vendor->name }}</i>
            </h3>
            <h5 class="mb-2 text-gray-lighter">
                <span>{{ $vendorCategory->name }}&nbsp;({{ $products->total() }})</span>
                <span class="mx-4"><i class="fa fa-long-arrow-right fa-lg"></i></span>
                <span>{{ $localCategory->name }}&nbsp;
                    <span>({{ $synchronizedVendorProductsCount }})</span>
                </span>
            </h5>
        </div>
        <div class="col-auto admin-content-actions">
            <a href="{{ route('vendor.categories.index', ['vendorId' => $vendorCategory->vendor->id]) }}"
               data-toggle="tooltip" title="Отменить"
               class="btn btn-primary"><i class="fa fa-reply"></i></a>
        </div>
    </div>

    @if ($errors->any())
        <div class="row">
            <div class="col-sm-8">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="row">

        @if($products->count())
            <div class="col-lg-12 my-4">

                <div class="d-inline-block">
                    @if($products->links())
                        {{$products->links()}}
                    @endif
                </div>

                <div class="float-right">
                    <button class="btn btn-primary" type="submit" form="sync-products-form"
                            formaction="{{ route('vendor.category.products.upload') }}">Синхронизировать
                        выбранное
                    </button>
                    <button class="btn btn-primary ml-2" type="submit" form="sync-products-form"
                            formaction="{{ route('vendor.category.products.upload.all') }}">Синхронизировать все
                    </button>
                </div>
            </div>

            <div class="col-lg-12 my-4">
                @include('content.admin.vendors.category.products.parts.product_form')
            </div>

            @if($products->links())
                <div class="col-lg-12 my-4">{{$products->links()}}</div>
            @endif

        @endif

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // set all products selected
            $('#select-all-products').click(function () {

                let checkboxes = $('input[type="checkbox"]');

                if ($(this).is(':checked')) {
                    $(checkboxes).prop('checked', true);
                } else {
                    $(checkboxes).prop('checked', false);
                }
            });

        });

    </script>

@endsection