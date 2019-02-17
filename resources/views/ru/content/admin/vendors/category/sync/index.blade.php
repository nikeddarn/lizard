@extends('layouts.admin')

@section('content')

    @if($errors->any())
        @include('elements.errors.admin_error.index')

    @else

        @include('content.admin.vendors.category.sync.parts.header')

        <div class="card card-body">
            @include('content.admin.vendors.category.sync.parts.form')
        </div>

    @endif

@endsection
