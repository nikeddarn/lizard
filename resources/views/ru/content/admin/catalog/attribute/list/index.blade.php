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
            let currentLink = $('#main-menu-catalog-attributes');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

            // toggle attribute checkbox properties
            $('.attribute-property-change').click(function (event) {
                event.stopImmediatePropagation();
                event.preventDefault();

                let changePropertyBlock = $(event.target).closest('.attribute-property-change').parent();
                let propertyOffButton = $(changePropertyBlock).find('.attribute-property-off');
                let propertyOnButton = $(changePropertyBlock).find('.attribute-property-on');

                $.ajax({
                    url: this,
                    success: function (data) {
                        if (data === '0') {
                            $(propertyOffButton).removeClass('d-inline-block').addClass('d-none');
                            $(propertyOnButton).removeClass('d-none').addClass('d-inline-block');
                        } else if (data === '1') {
                            $(propertyOnButton).removeClass('d-inline-block').addClass('d-none');
                            $(propertyOffButton).removeClass('d-none').addClass('d-inline-block');
                        }
                    }
                });
            });

        });
    </script>

@endsection
