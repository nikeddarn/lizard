@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.product_category.create.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">
        @include('content.admin.catalog.product_category.create.parts.category_form')
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

            // activate selectpicker
            $('.selectpicker').selectpicker();

        });

    </script>

@endsection
