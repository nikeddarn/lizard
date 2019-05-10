<div class="card card-body mb-4">

    <div class="d-flex justify-content-between align-items-start">

        <h1 class="h4 text-gray-hover">
            <span class="ml-4">Категория поставщика</span>
            <span>{{ $vendorCategory->vendor->name }}:</span>
            <span class="ml-2">{{ $vendorCategory->name }}</span>
        </h1>

        <div class="d-flex">

            <a href="{{ route('vendor.category.edit', ['vendorCategoriesId' => $vendorCategory->id]) }}"
               data-toggle="tooltip"
               title="Редактировать категорию поставщика" class="btn btn-primary ml-1">
                <i class="svg-icon-larger" data-feather="edit"></i>
            </a>

            <form class="delete-form" method="post" action="{{ route('vendor.category.delete') }}">
                @csrf
                @method('delete')
                <input type="hidden" name="vendorCategoriesId"
                       value="{{ $vendorCategory->id }}">
                <button type="submit"
                        data-toggle="tooltip"
                        title="Удалить категорию и все товары" class="btn btn-danger ml-1">
                    <i class="svg-icon-larger" data-feather="link-2"></i>
                </button>
            </form>

            <a href="{{ route('vendor.category.list', ['vendorId' => $vendorCategory->vendor->id]) }}" data-toggle="tooltip" title="Назад"
               class="btn btn-primary ml-1">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>

        </div>

    </div>

</div>
