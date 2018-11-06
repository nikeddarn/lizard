@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Присоединить категорию<i
                        class="ml-5 admin-content-sub-header">{{ $vendor->name }}: {{ $vendorCategory->name }}</i></h2>
        </div>
        @if(isset($vendorCategory))
            <div class="col-auto admin-content-actions">
                <button type="submit" form="category-form" data-toggle="tooltip" title="Сохранить"
                        class="btn btn-primary">
                    <i class="fa fa-save"></i></button>
                <a href="{{ url()->previous() }}" data-toggle="tooltip" title="Отменить"
                   class="btn btn-primary"><i class="fa fa-reply"></i></a>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-12">

            @if(isset($vendorCategory))
                @include('content.admin.vendors.category.sync.parts.form')
            @else
                <h4 class="text-gray">Не удалось получить данные от поставщика. Попробуйте позже</h4>
            @endif


        </div>
    </div>

@endsection