@extends('layouts.admin')

@section('content')

    @include('content.sale.orders.edit.add_product.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body mb-5">
        @include('content.sale.orders.edit.add_product.parts.form')
    </div>


    <div id="category-products"></div>


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

            $('#category_id').change(function () {
                let orderId = $(this).data('order-id');
                let categoryId = $(this).find('option:selected').val();

                $.ajax({
                    url: "{{ route('admin.order.category.products') }}",
                    method: "POST",
                    data: {
                        order_id: orderId,
                        category_id: categoryId
                    },
                    success: function (data) {
                        $('#category-products').html(data);
                        feather.replace();
                    }
                });
            })

        });

    </script>

@endsection
