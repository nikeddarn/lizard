@extends('layouts.admin')

@section('content')

    <div class="row justify-content-between admin-content-header">
        <div class="col admin-content-title"><h2>Категория:<i
                        class="ml-5 admin-content-sub-header">{{ $category->name }}</i></h2></div>
        <div class="col-auto admin-content-actions">

            <a href="{{ route('admin.categories.edit', ['id' => $category->id]) }}" data-toggle="tooltip"
               title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

            <form class="product-delete-form d-inline-block"
                  action="{{ route('admin.categories.destroy', ['id' => $category->id]) }}" method="post">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить категорию">
                    <i class="fa fa-trash-o"></i>
                </button>
            </form>

            <a href="{{ route('admin.categories.index') }}" data-toggle="tooltip" title="К списку категорий"
               class="btn btn-primary ml-lg-2"><i class="fa fa-reply"></i></a>

        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">
            @include('content.admin.catalog.category.show.parts.properties')
        </div>

        <div class="col-lg-12">
            @include('content.admin.catalog.category.show.parts.products')
        </div>

        <div class="col-lg-12">
            @include('content.admin.catalog.category.show.parts.filters')
        </div>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".product-delete-form").submit(function (event) {
                if (confirm('Удалить продукт ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            $(".category-filter-delete-form").submit(function (event) {
                if (confirm('Удалить фильтр категории ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

        });

    </script>

@endsection