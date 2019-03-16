@extends('layouts.admin')

@section('content')

    @include('content.admin.vendors.products.synchronization.parts.header')

    @include('elements.errors.admin_error.index')

    @if(!empty($vendorProcessingProducts))
        <div class="card card-body">
            @include('content.admin.vendors.products.synchronization.parts.product_list')
        </div>

        @if($vendorProcessingProducts->lastPage() !== 1)
            <div class="my-4">
                @include('layouts.parts.pagination.products.index', ['paginator' => $vendorProcessingProducts])
            </div>
        @endif

    @endif

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            // set all products selected
            $('#select-all-products').click(function () {

                let checkboxes = $('input[type="checkbox"]');

                if ($(this).is(':checked')) {
                    $(checkboxes).prop('checked', true);
                } else {
                    $(checkboxes).prop('checked', false);
                }
            });

// ------------------------------------ watching for uploading ---------------------------------------------------------


            // set watching interval
            let checkUploadingInterval = setInterval(function () {

                // get queued products checkboxes
                let queuedProductCheckboxes = $('#sync-products-form').find('.queued-product-checkbox');

                if (queuedProductCheckboxes.length) {
                    // get queued vendor products ids
                    let vendorProductsIds = $(queuedProductCheckboxes).map(function () {
                        return this.value;
                    }).get();

                    // get already uploaded products ids
                    getUploadedVendorProductsIds(vendorProductsIds);
                } else {
                    clearInterval(checkUploadingInterval);
                }
            }, 3000);

            function getUploadedVendorProductsIds(vendorProductsIds) {
                // request uploaded vendor products ids
                $.ajax({
                    type: 'POST',
                    url: '{{ route('vendor.category.products.downloaded.ids') }}',
                    data: {
                        'processing_vendor_products': JSON.stringify(vendorProductsIds),
                        'vendor_categories_id': $('input[name="vendor_categories_id"]').val(),
                        'local_categories_id': $('input[name="local_categories_id"]').val()
                    },
                    success: function (data) {
                        $.each(JSON.parse(data), function () {
                            markAsUploaded(this);
                        });
                    }
                });
            }

            function markAsUploaded(uploadedProductId) {
                $('#select-product-' + uploadedProductId).removeClass('queued-product-checkbox');
            }

        });

    </script>

@endsection
