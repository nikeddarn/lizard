@extends('layouts.common')

@section('content')

    <div class="container">

        <div class="card my-3">

            <div class="full-cart">
                <div class="card-header border-bottom bg-white p-0 d-flex justify-content-center align-items-center">
                    @include('content.shop.cart.parts.header')
                </div>

                <div id="full-cart-body" class="card-body px-1 px-md-5 pt-5">
                    @include('content.shop.cart.parts.body')
                </div>
            </div>

        </div>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            let changeCartContent = function (url) {
                $.ajax({
                    url: url,
                    success: function (data) {
                        $('#full-cart-body').html(data);
                        feather.replace();
                        // bind handler
                        $('.change-count').click(function (event) {
                            event.preventDefault();
                            event.stopImmediatePropagation();
                            changeCartContent(this);
                        });
                    }
                });
            };

            // increase and decrease count
            $('.change-count').click(function (event) {
                event.preventDefault();
                event.stopImmediatePropagation();
                changeCartContent(this);
            });

        });

    </script>
@endsection
