@extends('layouts.admin')

@section('content')

    @include('content.admin.vendors.category.list.parts.header')

    @include('elements.errors.admin_error.index')

    @if($vendorCategories->count())

        <div class="card card-body">
            @include('content.admin.vendors.category.list.parts.list')
        </div>

        @if($vendorCategories->lastPage() !== 1)
            <div class="my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $vendorCategories])
            </div>
        @endif

    @endif

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

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
            let currentLink = $('#main-menu-vendors-{{ $vendor->id }}-categories-downloaded');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });

    </script>

@endsection
