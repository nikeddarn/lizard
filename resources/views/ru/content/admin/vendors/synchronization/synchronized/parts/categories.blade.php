<div class="table-responsive">
    <table class="table m-0">

        <thead>
        <tr class="text-center">
            <td><strong>Поставщик</strong></td>
            <td><strong>Категория поставщика</strong></td>
            <td><strong>Локальная категория</strong></td>
            <td><strong>Синхронизировано</strong></td>
            <td><strong>Опубликовано</strong></td>
            <td><strong>Действия</strong></td>
        </tr>
        </thead>

        <tbody>

        @foreach($synchronizedCategories as $category)

            <tr class="text-center">
                <td>{{ $category->vendor_name }}</td>
                <td>{{ $category->vendor_category_name }}</td>
                <td>
                    <a href="{{ route('shop.category.leaf.index', ['url' => $category->local_category_url]) }}">{{ $category->local_category_name }}</a>
                </td>
                <td>{{ $category->products_count }}</td>
                <td>{{ $category->published_products_count }}</td>

                <td class="d-flex justify-content-center">

                    <div class="d-flex justify-content-around">
                        <a href="{{ route('vendor.category.products.sync', ['vendorId' => $category->vendor_id, 'localCategoryId' => $category->local_category_id, 'vendorCategoryId' => $category->vendor_category_id]) }}"
                           data-toggle="tooltip"
                           title="Синхронизировать продукты" class="btn btn-primary">
                            <i class="svg-icon-larger" data-feather="list"></i>
                        </a>

                        <a href="{{ route('vendor.category.products.downloaded', ['localCategoryId' => $category->local_category_id, 'vendorCategoryId' => $category->vendor_category_id]) }}"
                           data-toggle="tooltip" title="Загруженные продукты" class="btn btn-primary ml-1">
                            <i class="svg-icon-larger" data-feather="eye"></i>
                        </a>

                        <form
                            class="auto-add-products-off-form mx-1 {{ $category->auto_add_products ? 'd-inline-block' : 'd-none' }}"
                            action="{{ route('vendor.category.products.auto.off') }}" method="post">
                            @csrf
                            <input type="hidden" name="vendor_categories_id"
                                   value="{{ $category->vendor_category_id }}">
                            <input type="hidden" name="categories_id" value="{{ $category->local_category_id }}">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                    title="Выключить автодобавление новых продуктов">
                                <i class="svg-icon-larger" data-feather="refresh-cw"></i>
                            </button>
                        </form>

                        <form
                            class="auto-add-products-on-form mx-1 {{ $category->auto_add_products ? 'd-none' : 'd-inline-block' }}"
                            action="{{ route('vendor.category.products.auto.on') }}" method="post">
                            @csrf
                            <input type="hidden" name="vendor_categories_id"
                                   value="{{ $category->vendor_category_id }}">
                            <input type="hidden" name="categories_id" value="{{ $category->local_category_id }}">
                            <button type="submit" class="btn btn-primary btn-outline-theme" data-toggle="tooltip"
                                    title="Включить автодобавление новых продуктов">
                                <i class="svg-icon-larger" data-feather="refresh-cw"></i>
                            </button>
                        </form>

                        <form class="delete-form d-inline-block unlink-form ml-lg-2"
                              action="{{ route('vendor.category.local.delete') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="vendor_categories_id" value="{{ $category->vendor_category_id }}">
                            <input type="hidden" name="categories_id" value="{{ $category->local_category_id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                    title="Отсоединить и удалить товары">
                                <i class="svg-icon-larger" data-feather="link-2"></i>
                            </button>
                        </form>

                    </div>

                </td>
            </tr>

        @endforeach

        </tbody>
    </table>
</div>
