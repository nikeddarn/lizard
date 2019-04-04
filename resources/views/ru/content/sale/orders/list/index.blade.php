@extends('layouts.admin')

@section('content')

    @include('content.sale.orders.list.parts.header')

    <div class="card card-body">
        @include('content.sale.orders.list.parts.orders')
    </div>

    @if($orders->lastPage() !== 1)
        <div class="row">
            <div class="col-12 my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $orders])
            </div>
        </div>
    @endif

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
