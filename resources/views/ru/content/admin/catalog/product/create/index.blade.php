@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Создать продукт</h2></div>
        <div class="col-auto admin-content-actions">
            <button type="submit" form="product-form" data-toggle="tooltip" title="Сохранить" class="btn btn-primary">
                <i class="fa fa-save"></i></button>
            <a href="{{ route('admin.products.index') }}" data-toggle="tooltip" title="Отменить"
               class="btn btn-default"><i class="fa fa-reply"></i></a>
        </div>
    </div>

    @if ($errors->any())
        <div class="row">
            <div class="col-sm-8">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">

            <form id="product-form" class="multitab-form" method="post" action="{{ route('admin.products.store') }}"
                  role="form"
                  enctype="multipart/form-data">
                @csrf

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" data-toggle="tab" href="#product-general" role="tab"
                           aria-controls="product-general" aria-selected="true">Основное</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#product-seo" role="tab"
                           aria-controls="product-seo" aria-selected="false">SEO</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#product-content" role="tab"
                           aria-controls="product-content" aria-selected="false">Описание</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#product-images" role="tab"
                           aria-controls="product-images" aria-selected="false">Изображения</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#product-attributes" role="tab"
                           aria-controls="product-attributes" aria-selected="false">Атрибуты</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#product-filters" role="tab"
                           aria-controls="product-filters" aria-selected="false">Фильтры</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#product-price" role="tab"
                           aria-controls="product-price" aria-selected="false">Цены</a>
                    </div>
                </nav>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="product-general" role="tabpanel"
                         aria-labelledby="product-general-tab">
                        @include('content.admin.catalog.product.create.parts.general_inputs')
                    </div>
                    <div class="tab-pane fade" id="product-seo" role="tabpanel" aria-labelledby="product-seo-tab">
                        @include('content.admin.catalog.product.create.parts.seo_inputs')
                    </div>
                    <div class="tab-pane fade" id="product-content" role="tabpanel"
                         aria-labelledby="product-content-tab">
                        @include('content.admin.catalog.product.create.parts.content_inputs')
                    </div>
                    <div class="tab-pane fade" id="product-images" role="tabpanel"
                         aria-labelledby="product-images-tab">
                        @include('content.admin.catalog.product.create.parts.images')
                    </div>
                    <div class="tab-pane fade" id="product-attributes" role="tabpanel"
                         aria-labelledby="product-attributes-tab">
                        @include('content.admin.catalog.product.create.parts.attributes')
                    </div>
                    <div class="tab-pane fade" id="product-filters" role="tabpanel"
                         aria-labelledby="product-attributes-tab">
                        @include('content.admin.catalog.product.create.parts.filters')
                    </div>
                    <div class="tab-pane fade" id="product-price" role="tabpanel"
                         aria-labelledby="product-price-tab">
                        @include('content.admin.catalog.product.create.parts.price')
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Создать продукт</button>

            </form>

            {{-- input image template (hidden) --}}
            @include('content.admin.catalog.product.create.parts.image_input_template')

            {{-- input attribute template (hidden) --}}
            @include('content.admin.catalog.product.create.parts.attribute_input_template')

            {{-- input filter template (hidden) --}}
            @include('content.admin.catalog.product.create.parts.filter_input_template')

        </div>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript" src="/js/generate-url.js"></script>

    <script>
        $(document).ready(function () {

            // auto generate url
            $('#name_ru').generateUrl({
                urlField: '#url',
                emptyOnly: false
            });

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
            let productForm = $('.multitab-form');

            // show tab that has empty required inputs
            $('.admin-content-header').find('button[form="product-form"]').click(function () {
                checkMultiTabForm(productForm);
            });
            $(productForm).find('button[type="submit"]').click(function () {
                checkMultiTabForm(productForm);
            });


            // insert images
            $('#product-image-add-button').click(function () {

                // create new image item from template and show it
                let newImageItem = $('#product-image-input-template').find('.product-image-item').clone().removeClass('d-none');

                let productImagesList = $('#product-images-list');

                // define image index
                let imageIndex = 0;

                let productImagesSet = $(productImagesList).find('.product-image-item');

                if ($(productImagesSet).length > 0) {
                    imageIndex = parseInt($(productImagesSet).last().find('.image-priority input').attr('id').split('-').pop()) + 1;
                }

                let imagePriorityBlock = $(newImageItem).find('.image-priority');

                // set and show radio button
                $(imagePriorityBlock).find('input').attr('id', 'product-image-item-' + imageIndex).attr('value', imageIndex);
                $(imagePriorityBlock).find('label').attr('for', 'product-image-item-' + imageIndex);

                // append new image item to images list block
                $(productImagesList).append(newImageItem);

                // register input file element fields
                $(newImageItem).find('.input-file-block .image-preview-input input').change(function () {

                    // common action with file input field
                    inputImageChanged.call(this, false);

                    // show radio button
                    $(this).closest('.product-image-item').find('.image-priority').removeClass('d-none');

                    // set image as primary if it's single
                    if ($(productImagesList).find('.product-image-item .image-priority').not('.d-none').length === 1) {
                        $(imagePriorityBlock).find('input').attr('checked', 'checked');
                    }
                });

                // delete image item
                $(newImageItem).find('.product-image-item-delete').click(function () {
                    $(this).closest(newImageItem).remove();
                })
            });

            // insert attributes
            $('#product-attribute-add-button').click(function () {

                // create new attribute item from template and show it
                let newAttributeItem = $('#product-attribute-input-template').find('.product-attribute-item').clone().removeClass('d-none');
                // append new image item to images list block
                $('#product-attributes-list').append(newAttributeItem);
                // delete image item
                $(newAttributeItem).find('.product-attribute-item-delete').click(function () {
                    $(this).closest(newAttributeItem).remove();
                });

                // activate selectpicker
                $(newAttributeItem).find('.attribute-id-select').addClass('selectpicker').selectpicker();

                // add attribute values options on attribute selected
                $('.attribute-id-select').change(function (e) {
                    e.stopImmediatePropagation();

                    let attributeValues = $(this).find('option:selected').data('attribute-values');
                    let attributeValueSelect = $(this).closest(newAttributeItem).find('select.attribute-value-id-select');

                    // clear old option elements
                    if ($(attributeValueSelect).hasClass('selectpicker')) {
                        $(attributeValueSelect).empty();
                    }

                    // create new option elements
                    $.each(attributeValues, function (index, attributeValue) {
                        let optionElement = $('<option/>').attr('value', attributeValue.id).text(attributeValue.value);
                        $(attributeValueSelect).append(optionElement);
                    });

                    // activate selectpicker
                    $(attributeValueSelect).addClass('selectpicker').selectpicker('refresh');

                    // show select attribute values block
                    $(attributeValueSelect).closest('.row').removeClass('d-none');

                    // remove start select option
                    $(this).find('option[value="0"]').remove();
                    $(this).selectpicker('refresh');
                })

            });

            // insert filters
            $('#product-filter-add-button').click(function () {

                // create new filter item from template and show it
                let newFilterItem = $('#product-filter-input-template').find('.product-filter-item').clone().removeClass('d-none');
                // append new image item to images list block
                $('#product-filters-list').append(newFilterItem);
                // delete filter item
                $(newFilterItem).find('.product-filter-item-delete').click(function () {
                    $(this).closest(newFilterItem).remove();
                });

                // activate selectpicker
                $(newFilterItem).find('.filter-id-select').addClass('selectpicker').selectpicker();

            });


        });

    </script>

@endsection