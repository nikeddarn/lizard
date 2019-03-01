<div class="table-responsive">

    <table class="table">

        <thead>
        <tr class="text-center">
            <td><strong>Создан</strong></td>
            <td><strong>Загружен</strong></td>
            <td><strong>Архивный</strong></td>
            <td class="d-none d-lg-table-cell"><strong>Изображение</strong></td>
            <td><strong>Название</strong></td>
            <td><strong>Страна</strong></td>
            <td><strong>Гарантия</strong></td>
            <td><strong>Цена</strong></td>
            <td><strong>Прибыль ($)</strong></td>
            <td><strong>Прибыль (%)</strong></td>
            <td><strong>Действия</strong></td>
        </tr>
        </thead>

        <tbody>


        @foreach($downloadedVendorProducts as $vendorProduct)

            <tr class="text-center">

                <td>
                    @if($vendorProduct->vendor_created_at)
                        {{ $vendorProduct->vendor_created_at->format('d - m - Y') }}
                    @endif
                </td>

                <td>
                    @if($vendorProduct->created_at->format('d - m - Y'))
                        {{ $vendorProduct->created_at->format('d - m - Y') }}
                    @endif
                </td>

                <td>
                    @if($vendorProduct->is_archive)
                        <i class="svg-icon-larger" data-feather="archive"></i>
                    @endif
                </td>
                <td class="d-none d-lg-table-cell">
                    @if($vendorProduct->product->primaryImage)
                        <img src="{{ url('/storage/' . $vendorProduct->product->primaryImage->small) }}"
                             class="img-responsive table-image">
                    @endif
                </td>
                <td>{{ $vendorProduct->product->name }}</td>
                <td>{{ $vendorProduct->product->manufacturer }}</td>
                <td>{{ $vendorProduct->warranty }}</td>
                <td>{{ $vendorProduct->price }}</td>
                <td>{{ $vendorProduct->profit }}</td>
                <td>{{ $vendorProduct->profitPercents }}</td>
                <td class="d-flex justify-content-center align-items-center">

                    <form class="product-delete-form d-inline-block"
                          action="{{ route('vendor.category.products.destroy') }}" method="post">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="vendor_product_id" value="{{ $vendorProduct->id }}">
                        <input type="hidden" name="local_category_id" value="{{ $localCategory->id }}">

                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="svg-icon-larger" data-feather="link-2"></i>
                        </button>
                    </form>
                </td>

            </tr>

        @endforeach

        </tbody>
    </table>

</div>
