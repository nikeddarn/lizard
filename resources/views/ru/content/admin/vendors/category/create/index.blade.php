@extends('layouts.admin')

@section('content')

    @if($errors->any())
        @include('elements.errors.admin_error.index')

    @else

        @include('content.admin.vendors.category.create.parts.header')

        <div class="card card-body">
            <form id="create-category-form" method="post" action="{{ route('vendor.category.store') }}" role="form">

                @csrf

                <input type="hidden" name="vendors_id" value="{{ $vendor->id }}">
                <input type="hidden" name="vendor_own_category_id" value="{{ $vendorCategory->id }}">

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link show active" data-toggle="tab"
                           href="#vendor-category-download" role="tab"
                           aria-controls="vendor-category-download"
                           aria-selected="true">Загрузка</a>
                        <a class="nav-item nav-link" data-toggle="tab"
                           href="#vendor-category-publish" role="tab"
                           aria-controls="vendor-category-publish"
                           aria-selected="false">Публикация</a>
                    </div>
                </nav>

                <div class="tab-content">
                    <div class="tab-pane fade show active"
                         id="vendor-category-download" role="tabpanel"
                         aria-labelledby="vendor-category-download-tab">
                        @include('content.admin.vendors.category.create.parts.download')
                    </div>
                    <div class="tab-pane fade"
                         id="vendor-category-publish" role="tabpanel"
                         aria-labelledby="vendor-category-publish-tab">
                        @include('content.admin.vendors.category.create.parts.publish')
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Создать категорию</button>

            </form>
        </div>

    @endif

@endsection

@section('scripts')

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

            // activate Touch Spin
            $(".vendor-settings-input").TouchSpin({
                min: 0,
                max: 100,
                step: 0.1,
                decimals: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

            // activate Touch Spin
            $("#download-product-max-age").TouchSpin({
                min: 0,
                max: 60,
                step: 1,
                buttondown_class: "btn btn-primary h-100",
                buttonup_class: "btn btn-primary h-100"
            });

        });
    </script>

@endsection
