@extends('layouts.admin')

@section('content')

    @include('content.admin.export.hotline.products.search.parts.header')

    @if($products->count())
        <div class="card card-body my-4">
            @include('content.admin.export.hotline.products.search.parts.search')
        </div>

        <div class="card card-body">
            @include('content.admin.export.hotline.products.search.parts.product_form')
        </div>
    @endif

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // turn on product publish
            $('.products-publish-off-form').submit(function (event) {
                if (confirm('Выключить публикацию продукта ?')) {
                    let form = $(this);
                    let url = form.attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function () {
                            $(form).removeClass('d-inline-block').addClass('d-none');
                            $(form).closest('.product-actions').find('.products-publish-on-form').removeClass('d-none').addClass('d-inline-block');
                        }
                    });
                }
                event.preventDefault();
                return false;
            });

            // turn off product publish
            $('.products-publish-on-form').click(function (event) {
                if (confirm('Включить публикацию продукта ?')) {
                    let form = $(this);
                    let url = form.attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(),
                        success: function () {
                            $(form).removeClass('d-inline-block').addClass('d-none');
                            $(form).closest('.product-actions').find('.products-publish-off-form').removeClass('d-none').addClass('d-inline-block');
                        }
                    });
                }
                event.preventDefault();
                return false;
            });

            // activate admin menu
            let currentLink = $('#main-menu-export-hotline-sync-list');
            $(currentLink).addClass('active');

            $(currentLink).parents('.collapse').each(function () {
                $(this).addClass('show');
                $(this).siblings('a[aria-expanded]').attr('aria-expanded', 'true');
            });

        });

    </script>

@endsection
