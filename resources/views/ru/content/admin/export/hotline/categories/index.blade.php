@extends('layouts.admin')

@section('content')

    @include('content.admin.export.hotline.categories.parts.header')

    @include('elements.errors.admin_error.index')

    @if(session()->has('successful'))
        @include('elements.success.admin_success.index', ['message' => 'Категории успешно импортированы'])
    @endif

    <div class="card card-body">
        @include('content.admin.export.hotline.categories.parts.form')
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // the name of the file appear on select
            $(".custom-file-input").on("change", function () {
                let fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });

            // activate admin menu
            let currentLink = $('#main-menu-export-hotline-categories-tree');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });

    </script>

@endsection
