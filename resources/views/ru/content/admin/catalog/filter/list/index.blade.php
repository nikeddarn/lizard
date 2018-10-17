@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col admin-content-title"><h2>Фильтры продуктов</h2></div>
        <div class="col-auto admin-content-actions">
            <a class="btn btn-primary" href="{{ route('admin.filters.create') }}" data-toggle="tooltip"
               title="Создать фильтр">
                <i class="fa fa-plus"></i>&nbsp;
                <span>Создать фильтр</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @include('content.admin.catalog.filter.list.parts.filters_list')
        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

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