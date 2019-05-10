@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">

        <div class="col admin-content-title">
            <h2>Фильтр:<i class="ml-5 admin-content-sub-header">{{ $filter->name }}</i></h2>
        </div>

        <div class="col-auto admin-content-actions">

            <a href="{{ route('admin.filters.edit', ['id' => $filter->id]) }}" data-toggle="tooltip"
               title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

            <form class="d-inline-block filter-form"
                  action="{{ route('admin.filters.destroy', ['id' => $filter->id]) }}" method="post">
                @csrf
                <input type="hidden" name="_method" value="delete"/>
                <input type="hidden" name="id" value="{{ $filter->id }}">
                <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить фильтр">
                    <i class="fa fa-trash-o"></i>
                </button>
            </form>

            <a href="{{ route('admin.filters.index') }}" data-toggle="tooltip" title="К списку фильтров"
               class="btn btn-primary ml-lg-2"><i class="fa fa-reply"></i></a>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-12">
            @include('content.admin.catalog.filter.show.parts.properties')
        </div>

        <div class="col-lg-12">
            @include('content.admin.catalog.filter.show.parts.filter_products')
        </div>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".delete-item-form").submit(function (event) {
                if (confirm('Удалить продукт из фильтра ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            $(".filter-form").submit(function (event) {
                if (confirm('Удалить фильтр ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

        });

    </script>

@endsection