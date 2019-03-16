@extends('layouts.common')

@section('content')

    <div class="container">

        <div class="card my-3">

            <div class="full-card">
                <div class="card-header border-bottom bg-white p-0 d-flex justify-content-center align-items-center">
                    @include('content.shop.checkout.parts.header')
                </div>

                <div class="card-body d-flex justify-content-center pt-5">
                    @include('content.shop.checkout.parts.body')
                </div>
            </div>

        </div>

    </div>

@endsection

@section('styles')

    <link type="text/css" href="{{ url('/css/bootstrap-select.min.css') }}" rel="stylesheet">

@endsection

@section('scripts')

    <script src="{{ url('/js/bootstrap-select.js') }}"></script>

    <script>

        $(document).ready(function () {

            // $('.selectpicker').selectpicker();

            $('#delivery_type').find('input').change(function () {
                let deliveryAddressWrapper = $('#delivery-address-wrapper');
                let deliveryCityWrapper = $('#delivery-city-wrapper');

                switch (parseInt(this.value)) {
                    case 1:
                        $(deliveryAddressWrapper).addClass('d-none');
                        $(deliveryCityWrapper).addClass('d-none');
                        break;
                    case 2:
                        $(deliveryAddressWrapper).removeClass('d-none');
                        $(deliveryCityWrapper).removeClass('d-none');
                        $('.selectpicker').selectpicker();
                        break;
                    case 3:
                        $(deliveryCityWrapper).addClass('d-none');
                        $(deliveryAddressWrapper).removeClass('d-none');
                        break;
                }
            });
        });

    </script>

@endsection
