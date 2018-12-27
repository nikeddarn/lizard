<table class="table table-bordered m-0">

    <thead>
    <tr class="text-center">
        <td class="py-0">Локальная категория</td>
        <td class="py-0">Товаров</td>
        <td class="py-0">Действия</td>
    </tr>
    </thead>

    <tbody>

    @foreach($synchronizedCategories->where('own_vendor_category_id', $category->id)->all() as $synchronizedCategory)

        <tr class="text-center">
            <td class="py-0">{{ $synchronizedCategory->local_category_name }}</td>
            <td class="py-0">{{ $synchronizedCategory->products_count }}</td>
            <td class="py-0 sync-category-actions">

                <a href="{{ route('vendor.category.products.index', ['vendorId' => $vendor->id, 'localCategoryId' => $synchronizedCategory->local_category_id, 'vendorCategoryId' => $synchronizedCategory->vendor_category_id]) }}"
                   data-toggle="tooltip"
                   title="Синхронизировать продукты" class="btn btn-primary"><i class="fa fa-eye"></i></a>

                    <form class="auto-add-products-off-form ml-lg-2 {{ $synchronizedCategory->auto_add_products ? 'd-inline-block' : 'd-none' }}"
                          action="{{ route('vendor.category.products.auto.off') }}" method="post">
                        @csrf
                        <input type="hidden" name="vendor_categories_id" value="{{ $synchronizedCategory->vendor_category_id }}">
                        <input type="hidden" name="categories_id" value="{{ $synchronizedCategory->local_category_id }}">
                        <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                title="Выключить автодобавление новых продуктов">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </form>

                    <form class="auto-add-products-on-form ml-lg-2 {{ $synchronizedCategory->auto_add_products ? 'd-none' : 'd-inline-block' }}"
                          action="{{ route('vendor.category.products.auto.on') }}" method="post">
                        @csrf
                        <input type="hidden" name="vendor_categories_id" value="{{ $synchronizedCategory->vendor_category_id }}">
                        <input type="hidden" name="categories_id" value="{{ $synchronizedCategory->local_category_id }}">
                        <button type="submit" class="btn btn-primary btn-outline-theme" data-toggle="tooltip"
                                title="Включить автодобавление новых продуктов">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </form>

                <form class="d-inline-block unlink-form ml-lg-2"
                      action="{{ route('vendor.category.unlink') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="vendors_id" value="{{ $vendor->id }}">
                    <input type="hidden" name="vendor_categories_id" value="{{ $synchronizedCategory->vendor_category_id }}">
                    <input type="hidden" name="categories_id" value="{{ $synchronizedCategory->local_category_id }}">
                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Отвязать и удалить товары">
                        <i class="fa fa-unlink"></i>
                    </button>
                </form>

            </td>
        </tr>

    @endforeach

    </tbody>
</table>
