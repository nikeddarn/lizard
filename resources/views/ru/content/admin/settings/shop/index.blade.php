@extends('layouts.admin')

@section('content')

    @include('content.admin.settings.shop.parts.header')

    @include('elements.errors.admin_error.index')

    @if(session()->has('successful'))
        @include('elements.success.admin_success.index', ['message' => 'Настройки успешно сохранены'])
    @endif

    <div class="card card-body">

        <form id="settings-form" class="multitab-form" method="post"
              action="{{ route('admin.settings.shop.update') }}" role="form"
              enctype="multipart/form-data">

            @csrf

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#settings-shop-currency" role="tab"
                       aria-controls="settings-shop-currency" aria-selected="true">Валюты</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#settings-shop-product" role="tab"
                       aria-controls="settings-shop-product" aria-selected="false">Продукты</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#settings-shop-badges" role="tab"
                       aria-controls="multi_filter_category" aria-selected="false">Стикеры</a>
                </div>
            </nav>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="settings-shop-currency" role="tabpanel"
                     aria-labelledby="settings-shop-currency-tab">
                    @include('content.admin.settings.shop.parts.currency')
                </div>
                <div class="tab-pane fade" id="settings-shop-product" role="tabpanel"
                     aria-labelledby="settings-shop-product-tab">
                    @include('content.admin.settings.shop.parts.product')
                </div>
                <div class="tab-pane fade" id="settings-shop-badges" role="tabpanel"
                     aria-labelledby="settings-shop-badges-tab">
                    @include('content.admin.settings.shop.parts.badge')
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
            let currentLink = $('#main-menu-settings-shop');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

            // activate Touch Spin
            $("#manual-usd-rate").TouchSpin({
                step: 0.01,
                decimals: 2,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

            $("#update-rate-time").TouchSpin({
                min: 10,
                max: 1440,
                step: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

            $("#min-user-price-group").TouchSpin({
                min: 1,
                max: 3,
                step: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

            $(".default-bootstrap-select-input").TouchSpin({
                min: 1,
                max: 100,
                step: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

            // change additional fields on change exchange rate type
            $('input[type="radio"][name="get_exchange_rate"]').change(function () {
                if ($(this).val() === 'auto') {
                    // show update rate time block
                    $('#set-usd-rate-wrapper').stop(true, true).animate({
                        opacity: 0
                    }, 200, null, function () {
                        $(this).removeClass('d-block');
                        $('#update-rate-time-wrapper').css('opacity', 0).addClass('d-block').animate({
                            opacity: 1
                        }, 200);
                    });
                } else if ($(this).val() === 'manual') {
                    // show set exchange rate block
                    $('#update-rate-time-wrapper').stop(true, true).animate({
                        opacity: 0
                    }, 200, null, function () {
                        $(this).removeClass('d-block');
                        $('#set-usd-rate-wrapper').css('opacity', 0).addClass('d-block').animate({
                            opacity: 1
                        }, 200);
                    });
                }
            });

            // hide and show set exchange rate input field
            $('.multi-inputs-checkbox').change(function () {
                let relatedInput = $(this).closest('.card').find('.multi-inputs-related-field');

                if ($(this).is(':checked')) {
                    // show set exchange rate input
                    $(relatedInput).css('opacity', 0).removeClass('invisible').addClass('visible').stop(true, true).animate({
                        opacity: 1
                    }, 200)
                }else {
                    // hide set exchange rate input
                    $(relatedInput).stop(true, true).animate({
                        opacity: 0
                    }, 200, null, function () {
                        $(this).removeClass('visible').addClass('invisible');
                    })
                }
            });

        });
    </script>

@endsection
