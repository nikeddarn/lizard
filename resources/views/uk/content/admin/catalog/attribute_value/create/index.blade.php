@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.attribute_value.create.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">
        @include('content.admin.catalog.attribute_value.create.parts.create_form')
    </div>

@endsection

@section('scripts')

    <script type="text/javascript" src="{{ url('/js/generate-url.js') }}"></script>

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
