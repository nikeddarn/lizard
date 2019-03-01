@extends('layouts.admin')

@section('content')

    @include('content.admin.vendors.products.downloaded.parts.header')

    @include('elements.errors.admin_error.index')

    @if($downloadedVendorProducts->count())
        <div class="card card-body">
            @include('content.admin.vendors.products.downloaded.parts.product_list')
        </div>

        @if($downloadedVendorProducts->lastPage() !== 1)
            <div class="my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $vendorProcessingProducts])
            </div>
        @endif

    @endif


@endsection

@section('scripts')

    <script>

        $(document).ready(function () {



        });

    </script>

@endsection
