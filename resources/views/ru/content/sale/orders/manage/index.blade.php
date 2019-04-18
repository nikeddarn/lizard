@extends('layouts.admin')

@section('content')

    @include('content.sale.orders.manage.parts.header')

    <div class="card card-body my-4">

        <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item">
                <a class="nav-link active" id="products-tab" data-toggle="tab" href="#products" role="tab"
                   aria-controls="products"
                   aria-selected="true">Товары</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="recipient-tab" data-toggle="tab" href="#recipient" role="tab"
                   aria-controls="recipient" aria-selected="false">Получатель</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab"
                   aria-controls="address" aria-selected="false">Доставка</a>
            </li>

        </ul>

        <div class="tab-content">

            <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                @include('content.sale.orders.manage.parts.products')
            </div>

            <div class="tab-pane fade" id="recipient" role="tabpanel" aria-labelledby="recipient-tab">
                @include('content.sale.orders.manage.parts.recipient')
            </div>

            <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                @include('content.sale.orders.manage.parts.address')
            </div>

        </div>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // activate admin menu
            let currentLink = $('#main-menu-shop-orders');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

            // confirm cancel order
            $('.cancel-order').submit(function (event) {
                if (confirm('Отменить заказ ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            });

            // confirm collect order
            $('.collect-order').submit(function (event) {
                if (confirm('Заказ обработан? Создать накладные ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            });

            // confirm delete product
            $('.delete-product').submit(function (event) {
                if (confirm('Удалить продукт ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            });

        });

    </script>

@endsection
