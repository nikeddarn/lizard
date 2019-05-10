@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.virtual_category.show.parts.header')

    @include('content.admin.catalog.virtual_category.show.parts.properties')

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
