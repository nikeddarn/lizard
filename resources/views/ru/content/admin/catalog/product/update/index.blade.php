@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.product.update.parts.header')

    @include('elements.errors.admin_error.index')

        <div class="card card-body">

            <form id="product-form" class="multitab-form" method="post" action="{{ route('admin.products.update', ['id' => $product->id]) }}"
                  role="form">

                @csrf

                @method('PUT')

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" data-toggle="tab" href="#product-general" role="tab"
                           aria-controls="product-general" aria-selected="true">Основное</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#product-seo" role="tab"
                           aria-controls="product-seo" aria-selected="false">SEO</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#product-content" role="tab"
                           aria-controls="product-content" aria-selected="false">Описание</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#product-price" role="tab"
                           aria-controls="product-price" aria-selected="false">Цены</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#product-parameters" role="tab"
                           aria-controls="product-parameters" aria-selected="false">Параметры</a>
                    </div>
                </nav>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="product-general" role="tabpanel"
                         aria-labelledby="product-general-tab">
                        @include('content.admin.catalog.product.update.parts.general_inputs')
                    </div>
                    <div class="tab-pane fade" id="product-seo" role="tabpanel" aria-labelledby="product-seo-tab">
                        @include('content.admin.catalog.product.update.parts.seo_inputs')
                    </div>
                    <div class="tab-pane fade" id="product-content" role="tabpanel"
                         aria-labelledby="product-content-tab">
                        @include('content.admin.catalog.product.update.parts.content_inputs')
                    </div>
                    <div class="tab-pane fade" id="product-price" role="tabpanel"
                         aria-labelledby="product-price-tab">
                        @include('content.admin.catalog.product.update.parts.price')
                    </div>
                    <div class="tab-pane fade" id="product-parameters" role="tabpanel"
                         aria-labelledby="product-parameters-tab">
                        @include('content.admin.catalog.product.update.parts.parameters')
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Сохранить изменения</button>

            </form>

        </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

            // activate text editor
            $('.summernote').summernote({
                height: 240,
                popover: {
                    image: [],
                    link: [],
                    air: []
                },
                callbacks: {
                    onImageUpload: function (files) {
                        let editor = this;
                        let data = new FormData();
                        data.append("image", files[0]);
                        $.post({
                            url: "{{ route('admin.products.upload.image') }}",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: data,
                            success: function (imageUrl) {
                                let image = $('<img>').attr('src', imageUrl);
                                $(editor).summernote("insertNode", image[0]);
                            }
                        })
                    }
                }
            });

            // focus on tab that has error in input field
            let categoryForm = $('.multitab-form');

            // show tab that has empty required inputs
            $('.admin-content-header').find('button[form="product-form"]').click(function () {
                checkMultiTabForm(categoryForm);
            });
            $(categoryForm).find('button[type="submit"]').click(function () {
                checkMultiTabForm(categoryForm);
            });
        });

    </script>

@endsection
