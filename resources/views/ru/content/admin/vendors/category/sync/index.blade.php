@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Присоединить категорию<i
                        class="ml-5 admin-content-sub-header">{{ $vendor->name }}: {{ $vendorCategory->name }}</i></h2>
        </div>
        @if(isset($vendorCategory))
            <div class="col-auto admin-content-actions">
                <button type="submit" form="sync-category-form" data-toggle="tooltip" title="Синхронизировать категорию"
                        class="btn btn-primary">
                    <i class="fa fa-save"></i></button>
                <a href="{{ url()->previous() }}" data-toggle="tooltip" title="Отменить"
                   class="btn btn-primary"><i class="fa fa-reply"></i></a>
            </div>
        @endif
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

            @include('content.admin.vendors.category.sync.parts.form')

        </div>
    </div>

@endsection
