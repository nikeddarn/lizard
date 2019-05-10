@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.product_file.create.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">
        @include('content.admin.catalog.product_file.create.parts.form')
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {
            // the name of the file appear on select
            $(".custom-file-input").on("change", function () {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        });

    </script>

@endsection
