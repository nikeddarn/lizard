@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.category.list.parts.header')

    @if($categories->count())
        <div class="card card-body">
            <ul class="category-list p-0">
                @include('content.admin.catalog.category.list.parts.category_list', ['categories' => $categories])
            </ul>
        </div>
    @endif

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

            // // turn on product publish
            // $('.category-publish-off-form').submit(function (event) {
            //     if (confirm('Выключить публикацию категории ?')) {
            //         let form = $(this);
            //         let url = form.attr('action');
            //
            //         $.ajax({
            //             type: "POST",
            //             url: url,
            //             data: form.serialize(),
            //             success: function () {
            //                 $(form).removeClass('d-inline-block').addClass('d-none');
            //                 $(form).closest('.category-actions').find('.category-publish-on-form').removeClass('d-none').addClass('d-inline-block');
            //             }
            //         });
            //     }
            //     event.preventDefault();
            //     return false;
            // });
            //
            // // turn off product publish
            // $('.category-publish-on-form').click(function (event) {
            //     if (confirm('Включить публикацию категории ?')) {
            //         let form = $(this);
            //         let url = form.attr('action');
            //
            //         $.ajax({
            //             type: "POST",
            //             url: url,
            //             data: form.serialize(),
            //             success: function () {
            //                 $(form).removeClass('d-inline-block').addClass('d-none');
            //                 $(form).closest('.category-actions').find('.category-publish-off-form').removeClass('d-none').addClass('d-inline-block');
            //             }
            //         });
            //     }
            //     event.preventDefault();
            //     return false;
            // });

            // confirm delete category
            $(".category-form").submit(function (event) {

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

            // activate admin menu
            let currentLink = $('#main-menu-catalog-categories');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });

    </script>

@endsection
