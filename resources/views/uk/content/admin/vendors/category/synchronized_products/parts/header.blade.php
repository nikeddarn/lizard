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

        <a href="{{ route('vendor.categories.index', ['vendorId' => $vendorCategory->vendor->id]) }}"
           data-toggle="tooltip" title="Назад" class="btn btn-primary">
            <i class="svg-icon-larger" data-feather="corner-up-left"></i>
        </a>

    </div>

</div>
