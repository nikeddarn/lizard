@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.attribute.update.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">
        @include('content.admin.catalog.attribute.update.parts.general_inputs')
    </div>

@endsection
