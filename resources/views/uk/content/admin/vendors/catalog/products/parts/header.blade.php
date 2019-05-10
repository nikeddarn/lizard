<div class="card card-body mb-4">

    <div class="d-flex justify-content-between align-items-start">

        <h1 class="h4 text-gray-hover">
            <span>Поставщик: {{ $vendor->name_ru }}</span>
            <span class="ml-2">Категория: {{ $vendorCategoryName }}</span>
        </h1>

        <div class="col-auto d-flex justify-content-center align-items-start">

            @if(isset($vendorCategoriesId))
                <a href="{{ route('vendor.category.show', ['vendorCategoriesId' => $vendorCategoriesId]) }}"
                   data-toggle="tooltip"
                   title="Смотреть загруженную категорию" class="btn btn-primary ml-1">
                    <i class="svg-icon-larger" data-feather="eye"></i>
                </a>

                <form class="delete-form" method="post"
                      action="{{ route('vendor.category.delete') }}">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="vendorCategoriesId"
                           value="{{ $vendorCategoriesId }}">
                    <button type="submit"
                            data-toggle="tooltip"
                            title="Удалить категорию и все товары" class="btn btn-danger ml-1">
                        <i class="svg-icon-larger" data-feather="link-2"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('vendor.category.create', ['id' => $vendor->id, 'vendorOwnCategoryId' => $vendorOwnCategoryId]) }}"
                   data-toggle="tooltip"
                   title="Синхронизировать" class="btn btn-primary ml-1">
                    <i class="svg-icon-larger" data-feather="link"></i>
                </a>
            @endif

            <a href="{{ route('vendor.catalog.categories.tree', ['vendorId' => $vendor->id]) }}"
               data-toggle="tooltip" title="К дереву категорий" class="btn btn-primary ml-1">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>
        </div>

    </div>

</div>
