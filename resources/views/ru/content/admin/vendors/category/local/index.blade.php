@extends('layouts.admin')

@section('content')

    @include('content.admin.vendors.category.local.parts.header')

    @if($errors->any())
        @include('elements.errors.admin_error.index')
    @endif

    <div class="card card-body">
        @include('content.admin.vendors.category.local.parts.form')
    </div>

@endsection
