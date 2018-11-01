@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">

        <div class="col admin-content-title">
            <h2>Создать значение атрибута:<i class="ml-5 admin-content-sub-header">{{ $attribute->name }}</i></h2>
        </div>

        <div class="col-auto admin-content-actions">
            <a href="{{ route('admin.attributes.show', ['id' => $attribute->id]) }}" data-toggle="tooltip" title="Назад"
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
            @include('content.admin.catalog.attribute_value.create.parts.create_form')
        </div>

    </div>

@endsection

@section('scripts')

    <script type="text/javascript" src="/js/generate-url.js"></script>

    <script>

        $(document).ready(function () {

            // auto generate url
            $('#value_ru').generateUrl({
                urlField: '#url',
                emptyOnly: false
            });

        });

    </script>

@endsection