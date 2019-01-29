@extends('layouts.admin')

@section('content')

    @include('content.admin.catalog.attribute.list.parts.header')

    @if($attributes->count())
        <div class="card card-body">
            @include('content.admin.catalog.attribute.list.parts.attributes_list')
        </div>
    @endif

    @if($attributes->lastPage() !== 1)
        <div class="row">
            <div class="col-12 my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $attributes])
            </div>
        </div>
    @endif

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".attribute-form").submit(function (event) {
                if (confirm('Удалить атрибут ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

            // activate admin menu
            let currentLink = $('#main-menu-shop-attributes');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });
    </script>

@endsection
