<table class="table table-bordered m-0">

    <thead>
    <tr class="text-center">
        <td class="py-0">Привязанная категория</td>
        <td class="py-0">Товаров</td>
        <td class="py-0">Действия</td>
    </tr>
    </thead>

    <tbody>

    @foreach($vendorSynchronizedCategories->get($category->id)->categories as $localCategory)

        <tr class="text-center">
            <td class="py-0">{{ $localCategory->name }}</td>
            <td class="py-0">{{ $localCategory->products_count }}</td>
            <td class="py-0 sync-category-actions">

                <a href="{{ route('vendor.category.products.index', ['vendorId' => $vendor->id, 'localCategoryId' => $localCategory->id, 'vendorCategoryId' => $vendorSynchronizedCategories->get($category->id)->id]) }}"
                   data-toggle="tooltip"
                   title="Синхронизировать продукты" class="btn btn-primary"><i class="fa fa-eye"></i></a>

                    <form class="auto-add-products-off-form ml-lg-2 {{ $localCategory->pivot->auto_add_new_products ? 'd-inline-block' : 'd-none' }}"
                          action="{{ route('vendor.category.products.auto.off') }}" method="post">
                        @csrf
                        <input type="hidden" name="vendor_categories_id" value="{{ $vendorSynchronizedCategories->get($category->id)->id }}">
                        <input type="hidden" name="categories_id" value="{{ $localCategory->id }}">
                        <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                title="Выключить автодобавление новых продуктов">
                            <i class="fa fa-download"></i>
                        </button>
                    </form>

                    <form class="auto-add-products-on-form ml-lg-2 {{ $localCategory->pivot->auto_add_new_products ? 'd-none' : 'd-inline-block' }}"
                          action="{{ route('vendor.category.products.auto.on') }}" method="post">
                        @csrf
                        <input type="hidden" name="vendor_categories_id" value="{{ $vendorSynchronizedCategories->get($category->id)->id }}">
                        <input type="hidden" name="categories_id" value="{{ $localCategory->id }}">
                        <button type="submit" class="btn btn-primary btn-outline-theme" data-toggle="tooltip"
                                title="Включить автодобавление новых продуктов">
                            <i class="fa fa-download"></i>
                        </button>
                    </form>

                <form class="d-inline-block unlink-form ml-lg-2"
                      action="{{ route('vendor.category.unlink') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="vendors_id" value="{{ $vendor->id }}">
                    <input type="hidden" name="vendor_categories_id" value="{{ $vendorSynchronizedCategories->get($category->id)->id }}">
                    <input type="hidden" name="categories_id" value="{{ $localCategory->id }}">
                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Отвязать и удалить товары">
                        <i class="fa fa-unlink"></i>
                    </button>
                </form>

            </td>
        </tr>

    @endforeach

    </tbody>
</table>