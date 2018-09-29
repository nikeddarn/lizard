@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">
        <div class="col-8 col-sm-8 col-md-9 col-lg-10 admin-content-title"><h2>Категории</h2></div>
        <div class="col-4 col-sm-4 col-md-3 col-lg-2 admin-content-actions">
            <a class="btn btn-primary" href="{{ route('admin.categories.create') }}" data-toggle="tooltip"
               title="Создать категорию">
                <i class="fa fa-plus"></i>&nbsp;
                <span>Создать</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @include('content.admin.catalog.category.list.parts.category_form')
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(".category-form").submit(function (event) {
            if (confirm('Удалить выбранные категории вместе с подкатегориями ?')) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        });
    </script>

@endsection