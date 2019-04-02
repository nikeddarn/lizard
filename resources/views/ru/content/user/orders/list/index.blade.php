@extends('layouts.user')

@section('content')

    <div class="card card-body my-4">

        <h1 class="h3 text-gray-hover m-0">Заказы</h1>

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
        @include('content.user.orders.list.parts.show_products_modal', ['order' => $order])
    @endforeach

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // confirm cancel order
            $('.cancel-order').submit(function (event) {
                if (confirm('Отменить заказ ?')) {
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

        });

    </script>
@endsection
