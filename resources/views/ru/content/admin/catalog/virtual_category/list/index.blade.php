@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.virtual_category.list.parts.header')

    @if($virtualCategories->count())
        <div class="card card-body">
            <ul class="category-list p-0">
                @include('content.admin.catalog.virtual_category.list.parts.category_list')
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

            // confirm delete category
            $("#delete-virtual-category-form").submit(function (event) {
                if (confirm('Удалить виртуальную категорию ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            // activate admin menu
            let currentLink = $('#main-menu-shop-virtual-categories');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });

    </script>

@endsection
