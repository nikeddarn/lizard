<table class="table table-bordered table-striped m-0">

    <thead>
    <tr class="text-center">
        <td><strong>Поставщик</strong></td>
        <td><strong>Категория поставщика</strong></td>
        <td><strong>Локальная категория</strong></td>
        <td><strong>Синхронизировано товаров</strong></td>
        <td><strong>Действия</strong></td>
    </tr>
    </thead>

    <tbody>

    @foreach($synchronizedCategories as $category)

            <tr class="text-center">
                <td>{{ $category->vendor_name }}</td>
                <td>{{ $category->vendor_category_name }}</td>
                <td>{{ $category->local_category_name }}</td>
                <td>{{ $category->products_count }}</td>

                <td class="py-0 sync-category-actions">

                    <a href="{{ route('vendor.category.products.index', ['vendorId' => $category->vendor_id, 'localCategoryId' => $category->local_category_id, 'vendorCategoryId' => $category->vendor_category_id]) }}"
                       data-toggle="tooltip"
                       title="Синхронизировать продукты" class="btn btn-primary"><i class="fa fa-eye"></i></a>

                    <form
                        class="auto-add-products-off-form ml-lg-2 {{ $category->auto_add_products ? 'd-inline-block' : 'd-none' }}"
                        action="{{ route('vendor.category.products.auto.off') }}" method="post">
                        @csrf
                        <input type="hidden" name="vendor_categories_id"
                               value="{{ $category->vendor_category_id }}">
                        <input type="hidden" name="categories_id" value="{{ $category->local_category_id }}">
                        <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                title="Выключить автодобавление новых продуктов">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </form>

                    <form
                        class="auto-add-products-on-form ml-lg-2 {{ $category->auto_add_products ? 'd-none' : 'd-inline-block' }}"
                        action="{{ route('vendor.category.products.auto.on') }}" method="post">
                        @csrf
                        <input type="hidden" name="vendor_categories_id"
                               value="{{ $category->vendor_category_id }}">
                        <input type="hidden" name="categories_id" value="{{ $category->local_category_id }}">
                        <button type="submit" class="btn btn-primary btn-outline-theme" data-toggle="tooltip"
                                title="Включить автодобавление новых продуктов">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </form>

                    <form class="d-inline-block unlink-form ml-lg-2"
                          action="{{ route('vendor.category.unlink') }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="vendors_id" value="{{ $category->vendor_id }}">
                        <input type="hidden" name="vendor_categories_id"
                               value="{{ $category->vendor_category_id }}">
                        <input type="hidden" name="categories_id" value="{{ $category->local_category_id }}">
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                title="Отвязать и удалить товары">
                            <i class="fa fa-unlink"></i>
                        </button>
                    </form>

                </td>
            </tr>

    @endforeach

    </tbody>
</table>
