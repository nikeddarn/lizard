@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Создать категорию</h2></div>
        <div class="col-auto admin-content-actions">
            <button type="submit" form="category-form" data-toggle="tooltip" title="Сохранить" class="btn btn-primary">
                <i class="fa fa-save"></i></button>
            <a href="{{ route('admin.categories.index') }}" data-toggle="tooltip" title="Отменить"
               class="btn btn-primary"><i class="fa fa-reply"></i></a>
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

            <form id="category-form" class="multitab-form" method="post" action="{{ route('admin.categories.store') }}" role="form"
                  enctype="multipart/form-data">
                @csrf

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#category-general" role="tab"
                       aria-controls="category-general" aria-selected="true">Основное</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#category-seo" role="tab"
                       aria-controls="category-seo" aria-selected="false">SEO</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#category-content" role="tab"
                       aria-controls="category-content" aria-selected="false">Описание</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#category-filters" role="tab"
                       aria-controls="category-filters" aria-selected="false">Фильтры</a>
                </div>
            </nav>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="category-general" role="tabpanel"
                         aria-labelledby="category-general-tab">
                        @include('content.admin.catalog.category.create.parts.general_inputs')
                    </div>
                    <div class="tab-pane fade" id="category-seo" role="tabpanel" aria-labelledby="category-seo-tab">
                        @include('content.admin.catalog.category.create.parts.seo_inputs')
                    </div>
                    <div class="tab-pane fade" id="category-content" role="tabpanel"
                         aria-labelledby="category-content-tab">
                        @include('content.admin.catalog.category.create.parts.content_inputs')
                    </div>
                    <div class="tab-pane fade" id="category-filters" role="tabpanel"
                         aria-labelledby="category-filters-tab">
                        @include('content.admin.catalog.category.create.parts.filters')
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Создать категорию</button>

            </form>

            {{-- input filter template (hidden) --}}
            @include('content.admin.catalog.category.create.parts.filter_input_template')

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
            let categoryForm = $('.multitab-form');

            // show tab that has empty required inputs
            $('.admin-content-header').find('button[form="category-form"]').click(function () {
                checkMultiTabForm(categoryForm);
            });
            $(categoryForm).find('button[type="submit"]').click(function () {
                checkMultiTabForm(categoryForm);
            });

            // insert filters
            $('#category-filter-add-button').click(function () {

                // create new filter item from template and show it
                let newFilterItem = $('#category-filter-input-template').find('.category-filter-item').clone().removeClass('d-none');
                // append new image item to images list block
                $('#category-filters-list').append(newFilterItem);
                // delete filter item
                $(newFilterItem).find('.category-filter-item-delete').click(function () {
                    $(this).closest(newFilterItem).remove();
                });

                // activate selectpicker
                $(newFilterItem).find('.filter-id-select').addClass('selectpicker').selectpicker();

            });

        });

    </script>

@endsection