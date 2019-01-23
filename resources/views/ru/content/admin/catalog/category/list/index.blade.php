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

            // confirm delete category
            $(".category-form").submit(function (event) {
                if (confirm('Удалить категорию вместе с подкатегориями ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

        });

    </script>

@endsection
