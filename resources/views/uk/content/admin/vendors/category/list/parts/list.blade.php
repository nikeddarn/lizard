<div class="table-responsive">
<table class="table">

    <thead>
    <tr class="text-center">
        <td><strong>Категория поставщика</strong></td>
        <td><strong>Локальных категорий</strong></td>
        <td><strong>Действия</strong></td>
    </tr>
    </thead>

    <tbody>

    @foreach($vendorCategories as $vendorCategory)

        <tr class="text-center">
            <td class="py-1">{{ $vendorCategory->name }}</td>
            <td class="py-1">{{ $vendorCategory->categories_count }}</td>
            <td class="py-1 d-flex justify-content-center align-items-start">

                <a href="{{ route('vendor.category.show', ['vendorCategoriesId' => $vendorCategory->id]) }}"
                   data-toggle="tooltip"
                   title="Смотреть загруженную категорию" class="btn btn-primary">
                    <i class="svg-icon-larger" data-feather="eye"></i>
                </a>

                <a href="{{ route('vendor.category.edit', ['vendorCategoriesId' => $vendorCategory->id]) }}"
                   data-toggle="tooltip"
                   title="Редактировать категорию поставщика" class="btn btn-primary ml-1">
                    <i class="svg-icon-larger" data-feather="edit"></i>
                </a>

                <a href="{{ route('vendor.category.local.create', ['vendorCategoryId' => $vendorCategory->id]) }}"
                   data-toggle="tooltip"
                   title="Присоединить локальную категорию" class="btn btn-primary ml-1">
                    <i class="svg-icon-larger" data-feather="link"></i>
                </a>

                <form class="delete-form" method="post" action="{{ route('vendor.category.delete') }}">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="vendorCategoriesId" value="{{ $vendorCategory->id }}">
                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                    <button type="submit"
                            data-toggle="tooltip"
                            title="Удалить категорию и все товары" class="btn btn-danger ml-1">
                        <i class="svg-icon-larger" data-feather="link-2"></i>
                    </button>
                </form>

            </td>
        </tr>

    @endforeach

    </tbody>
</table>
</div>

<div class="text-right mt-5">
    <a class="btn btn-primary" href="{{ route('vendor.catalog.categories.tree', ['vendorId' => $vendor->id]) }}">
        <i class="svg-icon-larger" data-feather="plus"></i>
        <span>Загрузить категорию поставщика</span>
    </a>
</div>
