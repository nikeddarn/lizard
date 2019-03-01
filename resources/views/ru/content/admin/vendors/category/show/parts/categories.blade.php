@if($vendorCategory->categories->count())

    <div class="table-responsive mt-4">

        <table class="table">

            <thead>
            <tr>
                <td><strong>Локальная категория</strong></td>
                <td class="text-center"><strong>Товаров</strong></td>
                <td class="text-center"><strong>Действия</strong></td>
            </tr>
            </thead>

            <tbody>

            @foreach($vendorCategory->categories as $category)

                <tr>

                    <td class="py-1">{{ $category->name }}</td>

                    <td class="py-1 text-center">{{ $category->products_count }}</td>

                    <td class="py-1 sync-category-actions d-flex justify-content-center align-items-start">

                        <a href="{{ route('vendor.category.products.sync', ['localCategoryId' => $category->id, 'vendorCategoryId' => $vendorCategory->id]) }}"
                           data-toggle="tooltip" title="Синхронизировать продукты" class="btn btn-primary">
                            <i class="svg-icon-larger" data-feather="list"></i>
                        </a>

                        <a href="{{ route('vendor.category.products.downloaded', ['localCategoryId' => $category->id, 'vendorCategoryId' => $vendorCategory->id]) }}"
                           data-toggle="tooltip" title="Загруженные продукты" class="btn btn-primary ml-1">
                            <i class="svg-icon-larger" data-feather="eye"></i>
                        </a>

                        <form
                            class="auto-add-products-off-form ml-lg-2 {{ $category->pivot->auto_add_new_products ? 'd-inline-block' : 'd-none' }}"
                            action="{{ route('vendor.category.products.auto.off') }}" method="post">
                            @csrf
                            <input type="hidden" name="vendor_categories_id" value="{{ $vendorCategory->id }}">
                            <input type="hidden" name="categories_id" value="{{ $category->id }}">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                    title="Выключить автодобавление новых продуктов">
                                <i class="svg-icon-larger" data-feather="repeat"></i>
                            </button>
                        </form>

                        <form
                            class="auto-add-products-on-form ml-lg-2 {{ $category->pivot->auto_add_new_products ? 'd-none' : 'd-inline-block' }}"
                            action="{{ route('vendor.category.products.auto.on') }}" method="post">
                            @csrf
                            <input type="hidden" name="vendor_categories_id" value="{{ $vendorCategory->id }}">
                            <input type="hidden" name="categories_id" value="{{ $category->id }}">
                            <button type="submit" class="btn btn-primary btn-outline-theme" data-toggle="tooltip"
                                    title="Включить автодобавление новых продуктов">
                                <i class="svg-icon-larger" data-feather="repeat"></i>
                            </button>
                        </form>

                        <form class="delete-form d-inline-block unlink-form ml-lg-2"
                              action="{{ route('vendor.category.local.delete') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="vendor_categories_id" value="{{ $vendorCategory->id }}">
                            <input type="hidden" name="categories_id" value="{{ $category->id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                    title="Отсоединить и удалить товары">
                                <i class="svg-icon-larger" data-feather="link-2"></i>
                            </button>
                        </form>

                    </td>
                </tr>

            @endforeach

            </tbody>
        </table>


    </div>

@endif

<div class="mt-5 text-right">
    <a class="btn btn-primary"
       href="{{ route('vendor.category.local.create', ['vendorCategoriesId' => $vendorCategory->id]) }}">
        <i class="svg-icon-larger" data-feather="plus"></i>
        <span>Присоединить локальную категорию</span>
    </a>
</div>
