@extends('layouts.admin')

@section('content')

    @include('content.admin.settings.vendors.parts.header')

    @include('elements.errors.admin_error.index')

    @if(session()->has('successful'))
        @include('elements.success.admin_success.index', ['message' => 'Настройки успешно сохранены'])
    @endif

    <div class="card card-body">

        <form id="settings-form" class="multitab-form" method="post"
              action="{{ route('admin.settings.vendor.update') }}"
              role="form"
              enctype="multipart/form-data">
            @csrf

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link show active" data-toggle="tab"
                       href="#settings-vendor-product" role="tab"
                       aria-controls="settings-vendor-product"
                       aria-selected="true">Продукты</a>
                    <a class="nav-item nav-link" data-toggle="tab"
                       href="#settings-vendor-price" role="tab"
                       aria-controls="settings-vendor-price"
                       aria-selected="true">Цены</a>
                </div>
            </nav>

            <div class="tab-content">
                <div class="tab-pane fade show active"
                     id="settings-vendor-product" role="tabpanel"
                     aria-labelledby="settings-vendor-product-tab">
                    @include('content.admin.settings.vendors.parts.product')
                </div>
                <div class="tab-pane fade"
                     id="settings-vendor-price" role="tabpanel"
                     aria-labelledby="settings-vendor-price-tab">
                    @include('content.admin.settings.vendors.parts.price')
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
            let currentLink = $('#main-menu-settings-vendors');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

            // activate Touch Spin
            $(".vendor-settings-input").TouchSpin({
                min: 0.1,
                max: 50,
                step: 0.1,
                decimals: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

            $(".vendor-settings-input-discount").TouchSpin({
                min: -100,
                max: 100,
                step: 0.1,
                decimals: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

        });
    </script>

@endsection
