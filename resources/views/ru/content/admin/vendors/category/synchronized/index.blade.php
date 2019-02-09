@extends('layouts.admin')

@section('content')

    @include('content.admin.vendors.category.synchronized.parts.header')

    <div class="card card-body my-4">
        @include('content.admin.vendors.category.synchronized.parts.categories')
    </div>

    @if($synchronizedCategories->lastPage() !== 1)
        @include('layouts.parts.pagination.products.index', ['paginator' => $synchronizedCategories])
    @endif

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {


            // confirm unlink category
            $(".unlink-form").submit(function (event) {
                if (confirm('Отвязать категорию поставщика и удалить все связанные товары ?')) {
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

            //turn on auto download products of synchronized categories
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

            // activate admin menu
            let currentLink = $('#main-menu-sync-categories');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });

    </script>

@endsection
