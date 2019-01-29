@extends('layouts.admin')

@section('content')

    @include('content.admin.settings.seo.parts.header')

    @include('elements.errors.admin_error.index')

    @if(session()->has('successful'))
        @include('elements.success.admin_success.index', ['message' => 'Настройки успешно сохранены'])
    @endif

    <div class="card card-body">

        <form id="settings-form" class="multitab-form" method="post"
              action="{{ route('admin.settings.seo.update') }}" role="form"
              enctype="multipart/form-data">

            @csrf

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#category" role="tab"
                       aria-controls="category" aria-selected="true">Категория</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#virtual-category" role="tab"
                       aria-controls="virtual-category" aria-selected="false">Виртуальная категория</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#multi_filter_category" role="tab"
                       aria-controls="multi_filter_category" aria-selected="false">Мульти фильтр категория</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#product" role="tab"
                       aria-controls="product" aria-selected="false">Продукт</a>
                </div>
            </nav>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="category" role="tabpanel"
                     aria-labelledby="category-tab">
                    @include('content.admin.settings.seo.parts.category')
                </div>
                <div class="tab-pane fade" id="virtual-category" role="tabpanel" aria-labelledby="virtual-category-tab">
                    @include('content.admin.settings.seo.parts.virtual_category')
                </div>
                <div class="tab-pane fade" id="multi_filter_category" role="tabpanel" aria-labelledby="multi_filter_category-tab">
                    @include('content.admin.settings.seo.parts.multi_filter_category')
                </div>
                <div class="tab-pane fade" id="product" role="tabpanel"
                     aria-labelledby="product-tab">
                    @include('content.admin.settings.seo.parts.product')
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>

        </form>

    </div>

@endsection

@section('scripts')

    <script>
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
            let currentLink = $('#main-menu-settings-seo');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });
    </script>

@endsection
