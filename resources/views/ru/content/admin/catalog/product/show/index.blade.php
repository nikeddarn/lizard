@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.product.show.parts.header')

    <div class="card card-body my-4">

        <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item">
                <a class="nav-link active" id="properties-tab" data-toggle="tab" href="#properties" role="tab"
                   aria-controls="properties"
                   aria-selected="true">Свойства</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="images-tab" data-toggle="tab" href="#images" role="tab"
                   aria-controls="images" aria-selected="false">Изображения</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="attributes-tab" data-toggle="tab" href="#attributes" role="tab"
                   aria-controls="attributes" aria-selected="false">Атрибуты</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="categories-tab" data-toggle="tab" href="#categories" role="tab"
                   aria-controls="categories" aria-selected="false">Категории</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="vendors-tab" data-toggle="tab" href="#vendors" role="tab"
                   aria-controls="vendors" aria-selected="false">Поставщики</a>
            </li>

        </ul>

        <div class="tab-content">

            <div class="tab-pane fade show active" id="properties" role="tabpanel" aria-labelledby="properties-tab">
                @include('content.admin.catalog.product.show.parts.properties')
            </div>

            <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images-tab">
                @include('content.admin.catalog.product.show.parts.images')
            </div>

            <div class="tab-pane fade" id="attributes" role="tabpanel" aria-labelledby="attributes-tab">
                @include('content.admin.catalog.product.show.parts.attributes')
            </div>

            <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                @include('content.admin.catalog.product.show.parts.categories')
            </div>

            <div class="tab-pane fade" id="vendors" role="tabpanel" aria-labelledby="vendors-tab">
                @include('content.admin.catalog.product.show.parts.vendors')
            </div>

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
