<div class="card card-body mb-4">

    <div class="d-flex justify-content-between align-items-start">

        <h1 class="h4 text-gray-hover">
            <span class="d-inline-block">
                <span>Категория {{ $vendorCategory->vendor->name }}:</span>
                <span class="ml-2">{{ $vendorCategory->name }}</span>
                @if(!empty($vendorProcessingProducts))
                    <span class="ml-2">({{ $vendorProcessingProducts->total() }})</span>
                @endif
            </span>
            <span class="d-inline-block ml-5">
                <span>Локальная категория:</span>
                <span class="ml-2">{{ $localCategory->name }}</span>
                @if(!empty($totalSynchronizedProductsCount))
                    <span class="ml-2">({{ $totalSynchronizedProductsCount }})</span>
                @endif
                </span>
        </h1>

        <div class="d-flex align-items-start">
            <button class="btn btn-primary ml-2 px-4" type="submit" form="sync-products-form"
                    formaction="{{ route('vendor.category.products.download.all') }}"
                    title="Синхронизировать все продукты">
                <i class="svg-icon-larger" data-feather="chevrons-down"></i>
            </button>
            <button class="btn btn-primary ml-2 px-4" type="submit" form="sync-products-form"
                    formaction="{{ route('vendor.category.products.download.selected') }}"
                    title="Синхронизировать выбранные продукты">
                <i class="svg-icon-larger" data-feather="check-square"></i>
            </button>
            <a href="{{ route('vendor.category.show', ['vendorCategoriesId' => $vendorCategory->id]) }}"
               data-toggle="tooltip" title="Назад" class="btn btn-primary ml-4">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>
        </div>

    </div>

</div>
