@extends('layouts.admin')

@section('content')

    @include('content.sale.orders.edit.product.parts.header')

    <div class="card card-body">
        @include('content.sale.orders.edit.product.parts.form')
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

            $(".default-bootstrap-select-input").TouchSpin({
                min: 1,
                max: 100,
                step: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

        });

    </script>

@endsection
