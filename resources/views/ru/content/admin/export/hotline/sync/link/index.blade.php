@extends('layouts.admin')

@section('content')

    @include('content.admin.export.hotline.sync.link.parts.header')

    @include('elements.errors.admin_error.index')

    @if($dealerCategories->count())
        <div class="card card-body">
            <h4 class="text-center text-gray-hover">Выберете категорию Hotline</h4>
            <ul class="category-list p-0">
                @include('content.admin.export.hotline.sync.link.parts.hotline_categories_list', ['dealerCategories' => $dealerCategories])
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

        });

    </script>

@endsection
