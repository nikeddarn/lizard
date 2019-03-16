@extends('layouts.admin')

@section('content')

    @include('content.admin.vendors.products.downloaded.parts.header')

    @include('elements.errors.admin_error.index')

    @if($downloadedVendorProducts->count())
        <div class="card card-body px-2">
            @include('content.admin.vendors.products.downloaded.parts.product_list')
        </div>

        @if($downloadedVendorProducts->lastPage() !== 1)
            <div class="my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $downloadedVendorProducts])
            </div>
        @endif

    @endif


@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            $(".product-delete-form").submit(function (event) {
                if (confirm('Овязать продукт от поставщика ?')) {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });

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
                            $(form).closest('tr').find('.product-published').html('<i class="svg-icon-larger text-danger" data-feather="eye"></i>');
                            feather.replace();
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
                            $(form).closest('tr').find('.product-published').html('<i class="svg-icon-larger text-success" data-feather="eye"></i>');
                            feather.replace();
                        }
                    });
                }
                event.preventDefault();
                return false;
            });

        });

    </script>

@endsection
