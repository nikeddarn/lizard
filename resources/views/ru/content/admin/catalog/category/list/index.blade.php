@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">

        <div class="col admin-content-title"><h2>Категории</h2></div>
        <div class="col-auto admin-content-actions">
            <a class="btn btn-primary" href="{{ route('admin.categories.create') }}" data-toggle="tooltip"
               title="Создать категорию">
                <i class="fa fa-plus"></i>&nbsp;
                <span>Создать категорию</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @if($categories->count())
                <ul class="category-list p-0">
                    @include('content.admin.catalog.category.list.parts.category_list', ['categories' => $categories])
                </ul>
            @endif
        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".category-form").submit(function (event) {
                if (confirm('Удалить категорию вместе с подкатегориями ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

        });

    </script>

@endsection