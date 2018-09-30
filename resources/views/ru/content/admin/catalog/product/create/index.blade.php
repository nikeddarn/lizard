@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col-8 col-sm-8 col-md-9 col-lg-10 admin-content-title"><h2>Создать продукт</h2></div>
        <div class="col-4 col-sm-4 col-md-3 col-lg-2 admin-content-actions">
            <button type="submit" form="category-form" data-toggle="tooltip" title="Сохранить" class="btn btn-primary">
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

            <form id="category-form" method="post" action="{{ route('admin.products.store') }}" role="form"
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
                           aria-controls="product-attributes" aria-selected="false">Характеристики</a>
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
                    <div class="tab-pane fade" id="product-price" role="tabpanel"
                         aria-labelledby="product-price-tab">
                        @include('content.admin.catalog.product.create.parts.price')
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Создать продукт</button>

            </form>

        </div>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript" src="/js/input-file.js"></script>
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
                            url: "{{ route('admin.categories.upload.image') }}",
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
            let categoryForm = $('#category-form');
            $(categoryForm).find('button[type="submit"]').click(function () {

                $('input:invalid').each(function () {

                    // Find the tab-pane that this element is inside, and get the id
                    let tabId = $(this).closest('.tab-pane').attr('id');

                    // Find the link that corresponds to the pane and have it show
                    $(categoryForm).find('.nav a[href="#' + tabId + '"]').tab('show');

                    // Only want to do it once
                    return false;
                });
            });

        });

        // add product images handler
        $('.create-product-image-input').find('input').change(insertNewImage);

        function insertNewImage() {
            let imageItem = $(this).closest('.create-product-image-item');
            let newImageItem = $(imageItem).clone();

            let reader = new FileReader();
            reader.readAsDataURL(this.files[0]);

            reader.onload = function (e) {

                $(imageItem).find('.create-product-image-preview').removeClass('d-none').addClass('d-block')
                    .find('img').attr('src', e.target.result);

                $(imageItem).find('.create-product-image-input').addClass('d-none');

                // register delete button
                $(imageItem).find('.create-product-image-delete').click(function () {
                    $(imageItem).remove();
                });

                //append and register new image item
                $('#create-product-images-list').append(newImageItem);
                $(newImageItem).find('input').change(insertNewImage);
            };

        }

    </script>

@endsection