@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.category.show.parts.header')

    <div class="card card-body my-4">

        <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item">
                <a class="nav-link active" id="properties-tab" data-toggle="tab" href="#properties" role="tab"
                   aria-controls="properties"
                   aria-selected="true">Свойства</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="products-tab" data-toggle="tab" href="#products" role="tab"
                   aria-controls="products" aria-selected="false">Продукты</a>
            </li>

        </ul>

        <div class="tab-content">

            <div class="tab-pane fade show active" id="properties" role="tabpanel" aria-labelledby="properties-tab">
                @include('content.admin.catalog.category.show.parts.properties')
            </div>

            <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                @include('content.admin.catalog.category.show.parts.products')
            </div>

        </div>

    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // animate show subcategory button
            $('.show-subcategory').click(function () {
                let icon = $(this).find('.fa');
                if ($(icon).hasClass('fa-plus')) {
                    $(icon).removeClass('fa-plus').addClass('fa-minus');
                } else if ($(icon).hasClass('fa-minus')) {
                    $(icon).removeClass('fa-minus').addClass('fa-plus');
                }
            });

            // confirm delete category
            $(".category-delete-form").submit(function (event) {

                let checkCategoryUrl = $(this).data('check-empty-url');

                if (confirm('Удалить категорию вместе с подкатегориями и продуктами ?')) {
                    let response = $.ajax({
                        url: checkCategoryUrl,
                        async: false
                    }).responseText;

                    if (response === 'true') {
                        return true;
                    } else if (response === 'false') {
                        if (confirm('Категория не пустая (содержит продукты или подкатегории). Удалить категорию вместе со всеми подкатегориями и продуктами ?')) {
                            return true;
                        }else {
                            event.preventDefault();
                            return false;
                        }
                    } else {
                        event.preventDefault();
                        return false;
                    }

                } else {
                    event.preventDefault();
                    return false;
                }
            });

        });

    </script>

@endsection
