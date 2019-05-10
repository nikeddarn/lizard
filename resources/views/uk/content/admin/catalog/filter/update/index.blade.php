@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Изменить фильтр:<i class="ml-5 admin-content-sub-header">{{ $filter->name }}</i></h2></div>
        <div class="col-auto admin-content-actions">
            <button type="submit" form="filter-form" data-toggle="tooltip" title="Сохранить" class="btn btn-primary">
                <i class="fa fa-save"></i></button>
            <a href="{{ route('admin.filters.index') }}" data-toggle="tooltip" title="Отменить"
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

            <form id="filter-form" method="post" action="{{ route('admin.filters.update', ['id' => $filter->id]) }}" role="form">

                @csrf

                @method('PUT')

                @include('content.admin.catalog.filter.update.parts.general_inputs')

                <button type="submit" class="btn btn-primary">Сохранить изменения</button>

            </form>

        </div>
    </div>

@endsection