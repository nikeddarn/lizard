@extends('layouts.admin')

@section('content')

    @include('content.sale.orders.edit.address.parts.header')

    <div class="card card-body">
        @include('content.sale.orders.edit.address.parts.form')
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

            // change delivery type
            $('#delivery_type').find('input').change(function () {
                let deliveryStorageWrapper = $('#delivery-storage-wrapper');
                let deliveryAddressWrapper = $('#delivery-address-wrapper');
                let deliveryCityWrapper = $('#delivery-city-wrapper');

                switch (parseInt(this.value)) {
                    case 1:
                        $(deliveryStorageWrapper).removeClass('d-none');
                        $(deliveryAddressWrapper).addClass('d-none');
                        $(deliveryCityWrapper).addClass('d-none');
                        break;
                    case 2:
                        $(deliveryAddressWrapper).removeClass('d-none');
                        $(deliveryCityWrapper).removeClass('d-none');
                        $(deliveryStorageWrapper).addClass('d-none');
                        $('.selectpicker').selectpicker();
                        break;
                    case 3:
                        $(deliveryAddressWrapper).removeClass('d-none');
                        $(deliveryCityWrapper).addClass('d-none');
                        $(deliveryStorageWrapper).addClass('d-none');
                        break;
                }
            });

        });

    </script>

@endsection
