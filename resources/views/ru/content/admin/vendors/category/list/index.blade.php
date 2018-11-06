@extends('layouts.admin')

@section('content')

    <div class="row admin-content-header">

        <div class="col admin-content-title"><h2>Категории поставщика:<i
                        class="ml-5 admin-content-sub-header">{{ $vendor->name }}</i></h2></div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @if(isset($vendorCategories))
                <ul class="category-list p-0">
                    @include('content.admin.vendors.category.list.parts.category_list', ['categories' => $vendorCategories])
                </ul>
            @else
                <h4 class="text-gray">Не удалось получить данные от поставщика. Попробуйте позже</h4>
            @endif
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


            // confirm unlink category
            $(".unlink-form").submit(function (event) {
                if (confirm('Отвязать категорию поставщика ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            $(".delete-form").submit(function (event) {
                if (confirm('Отвязать категорию поставщика и удалить все связанные товары ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

        });

    </script>

@endsection