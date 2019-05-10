@extends('layouts.admin')

@section('content')

    @include('content.admin.vendors.catalog.tree.parts.header')

    @include('elements.errors.admin_error.index')

    @if(isset($vendorCategories))
        <div class="card card-body">
            <ul class="category-list p-0">
                @include('content.admin.vendors.catalog.tree.parts.categories', ['categories' => $vendorCategories])
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
            $(".delete-form").submit(function (event) {
                if (confirm('Отвязать категорию поставщика и удалить все связанные товары ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            // activate admin menu
            let currentLink = $('#main-menu-vendors-{{ $vendor->id }}-categories-tree');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });

    </script>

@endsection
