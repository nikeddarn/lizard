<div class="card card-body mb-4">

    <div class="d-flex justify-content-between align-items-start">

        <h1 class="h4 text-gray-hover">
            <span>Поставщик: {{ $vendor->name_ru }}</span>
            <span class="ml-2">Категория: {{ $vendorCategory->name }}</span>
        </h1>

        <div>
            <a href="{{ route('vendor.category.sync', ['id' => $vendor->id, 'vendorOwnCategoryId' => $vendorCategory->id]) }}"
               data-toggle="tooltip"
               title="Синхронизировать" class="btn btn-primary ml-1">
                <i class="svg-icon-larger" data-feather="link"></i>
            </a>

            <a href="{{ url()->previous() }}"
               data-toggle="tooltip" title="Отменить" class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>
        </div>

    </div>

</div>
