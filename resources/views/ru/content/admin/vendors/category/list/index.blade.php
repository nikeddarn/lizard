@extends('layouts.admin')

@section('content')

    @include('content.admin.vendors.category.list.parts.header')

    @include('elements.errors.admin_error.index')

    @if(isset($vendorCategories))
        <div class="card card-body">
            <ul class="category-list p-0">
                @include('content.admin.vendors.category.list.parts.categories', ['categories' => $vendorCategories])
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
                        success: function (data) {
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
                        success: function (data) {
                            $(form).removeClass('d-inline-block').addClass('d-none');
                            $(form).closest('.sync-category-actions').find('.auto-add-products-off-form').removeClass('d-none').addClass('d-inline-block');
                        }
                    });
                }
                event.preventDefault();
                return false;
            });

            // activate admin menu
            let currentLink = $('#main-menu-vendors-{{ $vendor->id }}-categories');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });

    </script>

@endsection
