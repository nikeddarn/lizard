@extends('layouts.admin')

@section('content')

    <div class="row justify-content-between admin-content-header">
        <div class="col admin-content-title"><h2>Продукт:<i
                        class="ml-5 admin-content-sub-header">{{ $product->name }}</i></h2></div>
        <div class="col-auto admin-content-actions">

            <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" data-toggle="tooltip"
               title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

            <form class="product-delete-form d-inline-block"
                  action="{{ route('admin.products.destroy', ['id' => $product->id]) }}" method="post">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                    <i class="fa fa-trash-o"></i>
                </button>
            </form>

            <a href="{{ route('admin.products.index') }}" data-toggle="tooltip" title="К списку продуктов"
               class="btn btn-primary ml-lg-2"><i class="fa fa-reply"></i></a>

        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">
            @include('content.admin.catalog.product.show.parts.properties')
        </div>

        <div class="col-lg-12">
            @include('content.admin.catalog.product.show.parts.categories')
        </div>

        <div class="col-lg-12">
            @include('content.admin.catalog.product.show.parts.images')
        </div>

        <div class="col-lg-12">
            @include('content.admin.catalog.product.show.parts.attributes')
        </div>

        <div class="col-lg-12">
            @include('content.admin.catalog.product.show.parts.filters')
        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".product-image-delete-form").submit(function (event) {
                if (confirm('Удалить изображение продукта ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            $(".product-attribute-delete-form").submit(function (event) {
                if (confirm('Удалить атрибут продукта ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            $(".product-filter-delete-form").submit(function (event) {
                if (confirm('Удалить фильтр продукта ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            // delete product from category
            let deleteCategoryForms = $(".product-category-delete-form");

            $(deleteCategoryForms).submit(function (event) {
                // prevent to delete single category
                if (deleteCategoryForms.length === 1){
                    alert('Запрещено удалять единственную категорию');
                    return false;
                }

                if (confirm('Удалить продукт из категории ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            $(".product-image-priority-form").submit(function (event) {
                if (confirm('Сделать изображение продукта основным ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });
        });

    </script>

@endsection