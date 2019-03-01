@extends('layouts.admin')

@section('content')
        @include('content.admin.vendors.category.show.parts.header')

        <div class="card card-body">

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">

                        <a class="nav-item nav-link show active" data-toggle="tab"
                           href="#local-categories" role="tab"
                           aria-controls="local-categories"
                           aria-selected="false">Локальные категории</a>
                        <a class="nav-item nav-link" data-toggle="tab"
                           href="#vendor-category-properties" role="tab"
                           aria-controls="vendor-category-properties"
                           aria-selected="true">Свойства</a>

                    </div>
                </nav>

                <div class="tab-content">

                    <div class="tab-pane fade show active"
                         id="local-categories" role="tabpanel"
                         aria-labelledby="local-categories-tab">
                        @include('content.admin.vendors.category.show.parts.categories')
                    </div>
                    <div class="tab-pane fade"
                         id="vendor-category-properties" role="tabpanel"
                         aria-labelledby="vendor-category-properties-tab">
                        @include('content.admin.vendors.category.show.parts.properties')
                    </div>

                </div>

        </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

            // focus on tab that has error in input field
            let categoryForm = $('.multitab-form');

            // show tab that has empty required inputs
            $('.admin-content-header').find('button[form="category-form"]').click(function () {
                checkMultiTabForm(categoryForm);
            });
            $(categoryForm).find('button[type="submit"]').click(function () {
                checkMultiTabForm(categoryForm);
            });

            // confirm unlink category
            $(".delete-form").submit(function (event) {
                if (confirm('Отвязать локальную категорию и удалить все связанные товары ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            // turn off auto download products of synchronized categories
            $('.auto-add-products-off-form').submit(function (event) {
                if (confirm('Выключить автодобавление новых продуктов ?')) {
                    let form = $(this);
                    let url = form.attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function () {
                            $(form).removeClass('d-inline-block').addClass('d-none');
                            $(form).closest('.sync-category-actions').find('.auto-add-products-on-form').removeClass('d-none').addClass('d-inline-block');
                        }
                    });
                }
                event.preventDefault();
                return false;
            });

            //turn off auto download products of synchronized categories
            $('.auto-add-products-on-form').click(function (event) {
                if (confirm('Включить автодобавление новых продуктов ?')) {
                    let form = $(this);
                    let url = form.attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function () {
                            $(form).removeClass('d-inline-block').addClass('d-none');
                            $(form).closest('.sync-category-actions').find('.auto-add-products-off-form').removeClass('d-none').addClass('d-inline-block');
                        }
                    });
                }
                event.preventDefault();
                return false;
            });

        });
    </script>

@endsection
