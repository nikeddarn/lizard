@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.product_video.create.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">
        @include('content.admin.catalog.product_video.create.parts.form')
    </div>

@endsection
