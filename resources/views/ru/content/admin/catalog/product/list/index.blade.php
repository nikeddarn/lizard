@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.product.list.parts.header')

    @if($products->count())
        <div class="card card-body">
            @include('content.admin.catalog.product.list.parts.product_form')
        </div>
    @endif

    @if($products->lastPage() !== 1)
        <div class="row">
            <div class="col-12 my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $products])
            </div>
        </div>
    @endif

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".product-delete-form").submit(function (event) {
                if (confirm('Удалить продукт ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });
        });

    </script>

@endsection
