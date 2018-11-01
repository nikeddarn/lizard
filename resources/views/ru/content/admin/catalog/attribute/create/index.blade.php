@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Создать атрибут</h2></div>
        <div class="col-auto admin-content-actions">
            <button type="submit" form="attribute-form" data-toggle="tooltip" title="Сохранить" class="btn btn-primary">
                <i class="fa fa-save"></i></button>
            <a href="{{ route('admin.attributes.index') }}" data-toggle="tooltip" title="Отменить"
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

            <form id="attribute-form" class="multitab-form" method="post" action="{{ route('admin.attributes.store') }}"
                  role="form"
                  enctype="multipart/form-data">
                @csrf

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" data-toggle="tab" href="#attribute-general" role="tab"
                           aria-controls="attribute-general" aria-selected="true">Основное</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#attribute-values" role="tab"
                           aria-controls="attribute-values" aria-selected="false">Значения</a>
                    </div>
                </nav>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="attribute-general" role="tabpanel"
                         aria-labelledby="attribute-general-tab">
                        @include('content.admin.catalog.attribute.create.parts.general_inputs')
                    </div>
                    <div class="tab-pane fade" id="attribute-values" role="tabpanel"
                         aria-labelledby="attribute-values-tab">
                        @include('content.admin.catalog.attribute.create.parts.values')
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Создать атрибут</button>

            </form>

            {{-- add attribute value template (hidden)--}}
            @include('content.admin.catalog.attribute.create.parts.value_template')

        </div>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript" src="/js/generate-url.js"></script>

    <script>

        $(document).ready(function () {

            let attributeForm = $('.multitab-form');

            // show tab that has empty required inputs
            $('.admin-content-header').find('button[form="attribute-form"]').click(function () {
                checkMultiTabForm(attributeForm);
            });
            $(attributeForm).find('button[type="submit"]').click(function () {
                checkMultiTabForm(attributeForm);
            });

            let attributeValueCounter = 0;

            // add new attribute value
            $('.attribute-value-add-item').click(function () {

                attributeValueCounter ++;

                // create new attribute value input
                let newItem = $('#attribute-value-template').find('.attribute-value-item').clone();

                // append new attribute item to list
                $('#attribute-value-list').append(newItem);

                // register input file element fields
                $(newItem).find('.input-file-block .image-preview-input input').change(inputImageChanged);

                // register deleting attribute button
                $(newItem).find('.attribute-value-item-delete').click(function () {
                    $(this).closest('.attribute-value-item').remove();
                });

                let generateUrlTargetClass = 'generateUrlTarget' + attributeValueCounter;
                $(newItem).find('.url').addClass(generateUrlTargetClass);

                // auto generate url
                $(newItem).find('.value_ru').generateUrl({
                    urlField: '.' + generateUrlTargetClass,
                    emptyOnly: false
                });

            });
        });

    </script>

@endsection