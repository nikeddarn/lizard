@extends('layouts.admin')

@section('content')

    @include('content.admin.settings.notifications.order.parts.header')

    @include('elements.errors.admin_error.index')

    @if(session()->has('successful'))
        @include('elements.success.admin_success.index', ['message' => 'Настройки успешно сохранены'])
    @endif

    <div class="row">
        <div class="col-sm-10 pr-sm-5">
            <div class="alert alert-info">
                <div class="mb-2">Допустимые ключи:</div>
                <ul class="list-unstyled">
                    <li><strong>USER_NAME</strong> - имя пользователя</li>
                    <li><strong>ORDER_ID</strong> - номер заказа</li>
                    <li><strong>ORDER_TOTAL_SUM</strong> - сумма заказа с доставкой</li>
                    <li><strong>CREATED_AT</strong> - дата создания заказа</li>
                    <li><strong>UPDATED_AT</strong> - дата изменения заказа</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card card-body">

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#settings-order-created" role="tab"
                       aria-controls="settings-order-created" aria-selected="true">Заказ создан</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#settings-order-updated" role="tab"
                       aria-controls="settings-order-updated" aria-selected="false">Заказ изменен</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#settings-order-deleted" role="tab"
                       aria-controls="settings-order-deleted" aria-selected="false">Заказ отменен</a>
                </div>
            </nav>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="settings-order-created" role="tabpanel"
                     aria-labelledby="settings-order-created-tab">
                    @include('content.admin.settings.notifications.order.parts.created')
                </div>
                <div class="tab-pane fade" id="settings-order-updated" role="tabpanel"
                     aria-labelledby="settings-order-updated-tab">
                    @include('content.admin.settings.notifications.order.parts.updated')
                </div>
                <div class="tab-pane fade" id="settings-order-deleted" role="tabpanel"
                     aria-labelledby="settings-order-deleted-tab">
                    @include('content.admin.settings.notifications.order.parts.deleted')
                </div>
            </div>

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
            let currentLink = $('#main-menu-notifications-order');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });
    </script>

@endsection
