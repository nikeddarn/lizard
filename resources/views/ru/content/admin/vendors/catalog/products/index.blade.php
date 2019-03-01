@extends('layouts.admin')

@section('content')

    @if(!$errors->any())
        @include('content.admin.vendors.catalog.products.parts.header')
    @endif

    @include('elements.errors.admin_error.index')

    @if(!empty($vendorCategoryProducts) && $vendorCategoryProducts->count())

        <div class="card card-body">
            @include('content.admin.vendors.catalog.products.parts.product_list')
        </div>

        @if($vendorCategoryProducts->lastPage() !== 1)
            <div class="my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $vendorCategoryProducts])
            </div>
        @endif

    @endif

@endsection
