@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Продукты</h2></div>
        <div class="col-auto admin-content-actions">
            <a class="btn btn-primary" href="{{ route('admin.products.create') }}" data-toggle="tooltip"
               title="Создать продукт">
                <i class="fa fa-plus"></i>&nbsp;
                <span>Создать продукт</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @include('content.admin.catalog.product.list.parts.product_form')
        </div>
    </div>

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