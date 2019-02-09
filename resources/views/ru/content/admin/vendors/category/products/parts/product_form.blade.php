<form id="sync-products-form" method="post">
    @csrf
    <input type="hidden" name="vendors_id" value="{{ $vendorCategory->vendor->id }}">
    <input type="hidden" name="vendor_categories_id" value="{{ $vendorCategory->id }}">
    <input type="hidden" name="local_categories_id" value="{{ $localCategory->id }}">
    <input type="hidden" name="vendor_own_category_id" value="{{ $vendorCategory->vendor_category_id }}">

    <div class="table-responsive">

        <table class="table">

            <thead>
            <tr class="text-center">
                <td class="d-none d-lg-table-cell"><strong>Изображение</strong></td>
                <td class="d-none d-lg-table-cell"><strong>Артикул</strong></td>
                <td><strong>Название</strong></td>
                <td><strong>Страна</strong></td>
                <td><strong>Гарантия</strong></td>
                <td><strong>Цена</strong></td>
                <td><strong>Прибыль ($)</strong></td>
                <td><strong>Прибыль (%)</strong></td>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input id="select-all-products" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label empty-checkbox-label" for="select-all-products"></label>
                    </div>
                </td>
            </tr>
            </thead>

            <tbody>


            @foreach($vendorProcessingProducts as $vendorOwnProduct)

                <input type="hidden" name="vendor_own_product_id[]" value="{{ $vendorOwnProduct->id }}">

                <tr class="text-center">

                    <td class="d-none d-lg-table-cell">
                        @if($vendorOwnProduct->image)
                            <img src="{{ $vendorOwnProduct->image }}" class="img-responsive table-image">
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell">{{ $vendorOwnProduct->articul }}</td>
                    <td>{{ $vendorOwnProduct->name }}</td>
                    <td>{{ $vendorOwnProduct->country }}</td>
                    <td>{{ $vendorOwnProduct->warranty }}</td>
                    <td>{{ $vendorOwnProduct->price }}</td>
                    <td>{{ $vendorOwnProduct->profit }}</td>
                    <td>{{ $vendorOwnProduct->profitPercents }}</td>
                    <td>
                        <div
                            class="custom-control custom-checkbox">

                            <input id="select-product-{{ $vendorOwnProduct->id }}" type="checkbox"
                                   class="custom-control-input{{ $vendorOwnProduct->queued ? ' queued-product-checkbox' : '' }}"
                                   name="selected_vendor_own_product_id[]"
                                   value="{{ $vendorOwnProduct->id }}" {{ $vendorOwnProduct->checked || $vendorOwnProduct->queued ? 'checked="checked"' : '' }}>

                            <label class="custom-control-label empty-checkbox-label"
                                   for="select-product-{{ $vendorOwnProduct->id }}"></label>
                        </div>
                    </td>

                </tr>

            @endforeach

            </tbody>
        </table>

    </div>

    <div class="my-4">
        <button class="btn btn-primary" type="submit" formaction="{{ route('vendor.category.products.upload') }}">
            Синхронизировать выбранное
        </button>
        <button class="btn btn-primary ml-2" type="submit"
                formaction="{{ route('vendor.category.products.upload.all') }}">Синхронизировать все
        </button>
    </div>

</form>
