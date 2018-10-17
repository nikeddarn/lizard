@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Создать фильтр</h2></div>
        <div class="col-auto admin-content-actions">
            <button type="submit" form="filter-form" data-toggle="tooltip" title="Сохранить" class="btn btn-primary">
                <i class="fa fa-save"></i></button>
            <a href="{{ route('admin.filters.index') }}" data-toggle="tooltip" title="Отменить"
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

            @include('content.admin.catalog.filter.create.parts.general_inputs')

        </div>
    </div>

@endsection

@section('scripts')

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

            // add new attribute value
            $('.attribute-value-add-item').click(function () {

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

            });
        });

    </script>

@endsection