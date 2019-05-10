@extends('layouts.admin')

@section('content')

    @include('content.admin.vendors.category.local.parts.header')

    @include('elements.errors.admin_error.index')

    <div class="card card-body">
        @include('content.admin.vendors.category.local.parts.form')
    </div>

@endsection
