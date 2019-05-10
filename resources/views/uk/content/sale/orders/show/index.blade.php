@extends('layouts.admin')

@section('content')

    @include('content.sale.orders.show.parts.header')

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
                @include('content.sale.orders.show.parts.products')
            </div>

            <div class="tab-pane fade" id="recipient" role="tabpanel" aria-labelledby="recipient-tab">
                @include('content.sale.orders.show.parts.recipient')
            </div>

            <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                @include('content.sale.orders.show.parts.address')
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

        });

    </script>

@endsection
