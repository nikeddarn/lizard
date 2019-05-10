<div class="card card-body mb-4">

    <div class="d-flex justify-content-between">

        <h1 class="h4 text-gray-hover">
            <span>Присоединить категорию</span>
            <span class="ml-4">{{ $vendor->name }}:</span>
            <span class="ml-2">{{ $vendorCategory->name }}</span>
        </h1>

        <div class="d-flex">

            <button type="submit" form="sync-category-form" data-toggle="tooltip"
                    title="Синхронизировать категорию"
                    class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="save"></i>
            </button>

            <a href="{{ url()->previous() }}" data-toggle="tooltip" title="Отменить"
               class="btn btn-primary ml-1">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>

        </div>

    </div>

</div>
