@extends('layouts.admin')

@section('content')

    @include('content.admin.page_content.product_group.create.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">

        <form id="group-form" class="multitab-form" method="post" action="{{ route('admin.product.group.store') }}"
              role="form">
            @csrf

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#group-appearance" role="tab"
                       aria-controls="group-appearance" aria-selected="true">Видимость</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#group-products" role="tab"
                       aria-controls="group-products" aria-selected="false">Продукты</a>
                </div>
            </nav>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="group-appearance" role="tabpanel"
                     aria-labelledby="group-appearance-tab">
                    @include('content.admin.page_content.product_group.create.parts.appearance')
                </div>
                <div class="tab-pane fade" id="group-products" role="tabpanel" aria-labelledby="group-products-tab">
                    @include('content.admin.page_content.product_group.create.parts.products')
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Создать группу товаров</button>

        </form>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript" src="{{ url('/js/generate-url.js') }}"></script>

    <script>

        $(document).ready(function () {

            // focus on tab that has error in input field
            let categoryForm = $('.multitab-form');

            // show tab that has empty required inputs
            $('.admin-content-header').find('button[form="category-form"]').click(function () {
                checkMultiTabForm(categoryForm);
            });
            $(categoryForm).find('button[type="submit"]').click(function () {
                checkMultiTabForm(categoryForm);
            });

            // activate touch spin
            $(".product-counts").TouchSpin({
                min: 1,
                max: 30,
                step: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

        });

    </script>

@endsection
