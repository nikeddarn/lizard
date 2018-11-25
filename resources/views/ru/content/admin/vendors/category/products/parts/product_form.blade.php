@if($vendorOwnProducts->count())

    <form id="sync-products-form" method="post">
        @csrf
        <input type="hidden" name="vendors_id" value="{{ $vendorCategory->vendor->id }}">
        <input type="hidden" name="vendor_categories_id" value="{{ $vendorCategory->id }}">
        <input type="hidden" name="local_categories_id" value="{{ $localCategory->id }}">

        <table class="table">

            <thead>
            <tr class="text-center">
                <td><strong>Код</strong></td>
                <td><strong>Название</strong></td>
                <td><strong>Страна</strong></td>
                <td><strong>Гарантия</strong></td>
                <td><strong>Цена</strong></td>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input id="select-all-products" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label empty-checkbox-label" for="select-all-products"></label>
                    </div>
                </td>
            </tr>
            </thead>

            <tbody>


            @foreach($vendorOwnProducts as $vendorOwnProduct)

                <input type="hidden" name="vendor_own_product_id[]" value="{{ $vendorOwnProduct->id }}">

                <tr>

                    <td class="text-center">{{ $vendorOwnProduct->code }}</td>
                    <td>{{ $vendorOwnProduct->name }}</td>
                    <td class="text-center">{{ $vendorOwnProduct->country }}</td>
                    <td class="text-center">{{ $vendorOwnProduct->warranty }}</td>
                    <td class="text-center">{{ $vendorOwnProduct->price }}</td>
                    <td class="text-center">
                        <div class="custom-control custom-checkbox">

                            <input id="select-product-{{ $vendorOwnProduct->id }}" type="checkbox"
                                   class="custom-control-input" name="selected_vendor_own_product_id[]"
                                   value="{{ $vendorOwnProduct->id }}" {{ $vendorOwnProduct->checked ? 'checked="checked"' : '' }}>

                            <label class="custom-control-label empty-checkbox-label"
                                   for="select-product-{{ $vendorOwnProduct->id }}"></label>
                        </div>
                    </td>

                </tr>

            @endforeach

            </tbody>
        </table>

        <button class="btn btn-primary" type="submit" formaction="{{ route('vendor.category.products.upload') }}">
            Синхронизировать выбранное
        </button>
        <button class="btn btn-primary ml-2" type="submit"
                formaction="{{ route('vendor.category.products.upload.all') }}">Синхронизировать все
        </button>

    </form>

@else

    <p>Нет ни одного продукта</p>

@endif