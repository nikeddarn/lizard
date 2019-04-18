@extends('layouts.admin')

@section('content')

    @include('content.sale.orders.edit.recipient.parts.header')

    <div class="card card-body">
        @include('content.sale.orders.edit.recipient.parts.form')
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
