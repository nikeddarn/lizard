@if($products->count())

    <form id="sync-products-form" method="post">
        @csrf
        <input type="hidden" name="vendors_id" value="{{ $vendorCategory->vendor->id }}">
        <input type="hidden" name="vendor_categories_id" value="{{ $vendorCategory->id }}">

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


            @foreach($products as $product)

                <tr>

                    <td class="text-center">{{ $product->product_code }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="text-center">{{ $product->country }}</td>
                    <td class="text-center">{{ $product->warranty }}</td>
                    <td class="text-center">{{ $product->price }}</td>
                    <td class="text-center">
                        <div class="custom-control custom-checkbox">

                            <input id="select-product-{{ $product->id }}" type="checkbox"
                                   class="custom-control-input" name="vendor_product_id[]"
                                   value="{{ $product->id }}" {{ $product->checked ? 'checked="checked"' : '' }}>

                            <label class="custom-control-label empty-checkbox-label"
                                   for="select-product-{{ $product->id }}"></label>
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