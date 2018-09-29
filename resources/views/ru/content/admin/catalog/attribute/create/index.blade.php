@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col-8 col-sm-8 col-md-9 col-lg-10 admin-content-title"><h2>Создать характеристику</h2></div>
        <div class="col-4 col-sm-4 col-md-3 col-lg-2 admin-content-actions">
            <button type="submit" form="category-form" data-toggle="tooltip" title="Сохранить" class="btn btn-primary">
                <i class="fa fa-save"></i></button>
            <a href="{{ route('admin.attributes.index') }}" data-toggle="tooltip" title="Отменить"
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

            <form id="category-form" method="post" action="{{ route('admin.attributes.store') }}" role="form"
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
                    <div class="tab-pane fade" id="attribute-values" role="tabpanel" aria-labelledby="attribute-values-tab">
                        @include('content.admin.catalog.attribute.create.parts.values')
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Создать характеристику</button>

            </form>

        </div>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript" src="/js/input-file.js"></script>

    <script>
        $(document).ready(function () {
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
    </script>

@endsection

@section('styles')
    <link href="/css/input-file.css" rel="stylesheet">
@endsection