@extends('layouts.admin')

@section('content')

    @if ($errors->any())

        <div class="row admin-content-header">

            <div class="col admin-content-title">

                <h3 class="mb-4">Поставщик:<i
                        class="ml-5 admin-content-sub-header">{{ $vendorCategory->vendor->name }}</i>
                </h3>

                <div class="row justify-content-around mb-2 text-gray-lighter">
                    <div class="col-12 col-md-auto">
                        <h5>Категория поставщика: {{ $vendorCategory->name }}</h5>
                    </div>
                    <div class="col-12 col-md-auto">
                        <h5>Локальная категория: {{ $localCategory->name }}</h5>
                    </div>
                </div>

            </div>

            <div class="col-auto admin-content-actions">
                <a href="{{ route('vendor.categories.index', ['vendorId' => $vendorCategory->vendor->id]) }}"
                   data-toggle="tooltip" title="Отменить"
                   class="btn btn-primary"><i class="fa fa-reply"></i></a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    @else

        <div class="row admin-content-header">

            <div class="col admin-content-title">

                <h3 class="mb-4">Поставщик:<i
                        class="ml-5 admin-content-sub-header">{{ $vendorCategory->vendor->name }}</i>
                </h3>

                <div class="row justify-content-around mb-2 text-gray-lighter">
                    <div class="col-12 col-md-auto">
                        <h5>Категория поставщика: {{ $vendorCategory->name }} ({{$vendorProcessingProducts->total()}}
                            )</h5>
                    </div>
                    <div class="col-12 col-md-auto">
                        <h5>Локальная категория: {{ $localCategory->name }} ({{ $totalSynchronizedProductsCount }})</h5>
                    </div>
                </div>

            </div>

            <div class="col-auto admin-content-actions">
                <a href="{{ route('vendor.categories.index', ['vendorId' => $vendorCategory->vendor->id]) }}"
                   data-toggle="tooltip" title="Отменить"
                   class="btn btn-primary"><i class="fa fa-reply"></i></a>
            </div>
        </div>


        <div class="row">

            @if($vendorProcessingProducts->count())
                <div class="col-lg-12 my-4">

                    <div class="d-inline-block">
                        @if($vendorProcessingProducts->links())
                            {{$vendorProcessingProducts->links()}}
                        @endif
                    </div>

                    <div class="float-right text-right">
                        <button class="btn btn-primary mb-2 mb-sm-0" type="submit" form="sync-products-form"
                                formaction="{{ route('vendor.category.products.upload') }}">Синхронизировать
                            выбранное
                        </button>
                        <button class="btn btn-primary ml-0 ml-sm-2" type="submit" form="sync-products-form"
                                formaction="{{ route('vendor.category.products.upload.all') }}">Синхронизировать все
                            продукты
                        </button>
                    </div>
                </div>

                <div class="col-lg-12 my-4">
                    @include('content.admin.vendors.category.products.parts.product_form')
                </div>

                @if($vendorProcessingProducts->links())
                    <div class="col-lg-12 my-4">{{$vendorProcessingProducts->links()}}</div>
                @endif

            @endif

        </div>

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
            }, 5000);

            function getUploadedVendorProductsIds(vendorProductsIds) {
                // request uploaded vendor products ids
                $.ajax({
                    type: 'POST',
                    url: '/admin/vendor/category/products/uploaded',
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
