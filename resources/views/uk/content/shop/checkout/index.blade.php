@extends('layouts.common')

@section('meta')

    @if(!empty($pageData['title_uk']))
        <title>{{ $pageData['title_uk'] }}</title>
    @endif

    @if(!empty($pageData['description_uk']))
        <meta name="description" content="{{ $pageData['description_uk'] }}">
    @endif

    @if(!empty($pageData['keywords_uk']))
        <meta name="keywords" content="{{ $pageData['keywords_uk'] }}">
    @endif

@endsection

@section('content')

    <div class="container">

        <div class="card my-3">

            <div class="full-cart">
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

            $('#delivery_type').find('input').change(function () {
                let deliveryStorageWrapper = $('#delivery-storage-wrapper');
                let deliveryAddressWrapper = $('#delivery-address-wrapper');
                let deliveryCityWrapper = $('#delivery-city-wrapper');
                let totalAmountField = $('#total-amount');
                let totalAmount = parseInt($(totalAmountField).data('amount'));

                switch (parseInt(this.value)) {
                    case 1:
                        $(deliveryStorageWrapper).removeClass('d-none');
                        $(deliveryAddressWrapper).addClass('d-none');
                        $(deliveryCityWrapper).addClass('d-none');

                        // set total sum as order sum
                        $(totalAmountField).html(new Intl.NumberFormat().format(totalAmount));
                        break;
                    case 2:
                        $(deliveryAddressWrapper).removeClass('d-none');
                        $(deliveryCityWrapper).removeClass('d-none');
                        $(deliveryStorageWrapper).addClass('d-none');
                        $('.selectpicker').selectpicker();

                        // add delivery sum to order sum
                        let deliverySum = parseInt($('#delivery_type').data('courier-delivery-sum'));
                        $(totalAmountField).html(new Intl.NumberFormat().format(totalAmount + deliverySum));
                        break;
                    case 3:
                        $(deliveryAddressWrapper).removeClass('d-none');
                        $(deliveryCityWrapper).addClass('d-none');
                        $(deliveryStorageWrapper).addClass('d-none');

                        // set total sum as order sum
                        $(totalAmountField).html(new Intl.NumberFormat().format(totalAmount));
                        break;
                }
            });

        });

    </script>

@endsection
