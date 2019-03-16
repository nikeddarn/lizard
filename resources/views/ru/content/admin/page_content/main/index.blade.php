@extends('layouts.admin')

@section('content')

    @include('content.admin.page_content.main.parts.header')

    @include('elements.errors.admin_error.index')

    @if(session()->has('successful'))
        @include('elements.success.admin_success.index', ['message' => 'Настройки успешно сохранены'])
    @endif

    <div class="card card-body">


        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link show active" data-toggle="tab"
                   href="#content-main-seo" role="tab"
                   aria-controls="content-main-seo"
                   aria-selected="true">Seo</a>

                <a class="nav-item nav-link" data-toggle="tab"
                   href="#content-main-slider" role="tab"
                   aria-controls="content-main-slider"
                   aria-selected="true">Слайдер</a>
                <a class="nav-item nav-link" data-toggle="tab"
                   href="#content-main-products" role="tab"
                   aria-controls="content-main-slider"
                   aria-selected="true">Группы товаров</a>
            </div>
        </nav>

        <div class="tab-content">
            <div class="tab-pane fade show active"
                 id="content-main-seo" role="tabpanel"
                 aria-labelledby="content-main-seo-tab">
                @include('content.admin.page_content.main.parts.seo')
            </div>

            <div class="tab-pane fade"
                 id="content-main-slider" role="tabpanel"
                 aria-labelledby="content-main-slider-tab">
                @include('content.admin.page_content.main.parts.slider')
            </div>

            <div class="tab-pane fade"
                 id="content-main-products" role="tabpanel"
                 aria-labelledby="content-main-products-tab">
                @include('content.admin.page_content.main.parts.products')
            </div>
        </div>

    </div>

@endsection

@section('scripts')

    <script src="{{ url('/js/jquery-ui.min.js') }}"></script>

    <script>

        // sort slides
        $(function () {
            let slides = $("#sortable-slides");

            $(slides).sortable({
                change: function( event) {
                    $('#sort-slide-button').removeClass('d-none');
                }
            });
        });

        // sort groups
        $(function () {
            let slides = $("#sortable-groups");

            $(slides).sortable({
                change: function( event) {
                    $('#sort-group-button').removeClass('d-none');
                }
            });
        });

        $(document).ready(function () {

            // focus on tab that has error in input field
            let categoryForm = $('.multitab-form');

            // show tab that has empty required inputs
            $('.admin-content-header').find('button[form="category-form"]').click(function () {
                checkMultiTabForm(categoryForm);
            });
            $(categoryForm).find('button[type="submit"]').click(function () {
                checkMultiTabForm(categoryForm);
            });

            // activate admin menu
            let currentLink = $('#main-menu-content-main');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

            //confirm delete slide
            $('.delete-slide-form').submit(function (event) {
                if (confirm('Удалить слайд ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    event.stopImmediatePropagation();

                    return false;
                }
            });

            //confirm delete group
            $('.delete-group-form').submit(function (event) {
                if (confirm('Удалить группу товаров ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    event.stopImmediatePropagation();

                    return false;
                }
            });

        });
    </script>

@endsection
