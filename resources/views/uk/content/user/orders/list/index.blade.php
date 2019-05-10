@extends('layouts.user')

@section('content')

    <div class="card card-body my-4">

        <h1 class="h3 text-gray-hover m-0">Замовлення</h1>

        <hr>

        @if(isset($orders) && $orders->count())
            @include('content.user.orders.list.parts.list')
        @endif

    </div>

    @if(isset($orders) && $orders->lastPage() !== 1)
        <div class="row">
            <div class="col-12 my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $orders])
            </div>
        </div>
    @endif

    @foreach($orders as $order)
        @include('content.user.orders.list.parts.update_products_modal', ['order' => $order])
        @include('content.user.orders.list.parts.update_delivery_modal', ['order' => $order])
        @include('content.user.orders.list.parts.show_products_modal', ['order' => $order])
    @endforeach

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // confirm cancel order
            $('.cancel-order').submit(function (event) {
                if (confirm('Скасувати замовлення ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            });

            // delete order product
            $('.product-remove').click(function () {
                $(this).closest('tr').remove();
            });

            //decrease product count
            $('.product-decrease').click(function () {
                let productInput = $(this).closest('.spinner').find('.product-count');
                let oldProductCount = parseInt($(productInput).val());
                let newProductCount = Math.max(oldProductCount - 1, 1);
                $(productInput).val(newProductCount);
            });

            //increase product count
            $('.product-increase').click(function () {
                let productInput = $(this).closest('.spinner').find('.product-count');
                let oldProductCount = parseInt($(productInput).val());
                let newProductCount = Math.max(oldProductCount + 1, 1);
                $(productInput).val(newProductCount);
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

            // activate modal with errors
            $('.modal-input-error').closest('.modal').modal('show');

        });

    </script>
@endsection
