@foreach($categories as $category)

    @if($category->children->count())
        <li class="card my-1">
            <div class="row">

                <div class="col-auto">
                    <button class="btn btn-primary show-subcategory" data-toggle="collapse"
                            data-target="#category-{{ $category->id }}">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="col">
                    @if($vendorSynchronizedCategories->has($category->id))
                        <span class="float-right px-1">
                            <span class="d-none d-lg-inline-block">Локальная категория:
                                <span class="ml-2"> {{ $vendorSynchronizedCategories->get($category->id)->localCategory->name }}</span>
                            </span>
                            <span class="ml-2 float-right px-1">(Продуктов: {{ $vendorSynchronizedCategories->get($category->id)->vendor_products_count }}
                                )</span>
                        </span>
                    @endif

                    <span>{{ $category->name }}</span>
                </div>

                <div class="col-auto">
                    @if($vendorSynchronizedCategories->has($category->id))

                        <a href="{{ route('vendor.category.products.index', ['id' => $vendor->id, 'categoryId' => $category->id]) }}"
                           data-toggle="tooltip"
                           title="Синхронизировать продукты" class="btn btn-primary"><i class="fa fa-list"></i></a>

                        <form class="d-inline-block unlink-form ml-lg-2"
                              action="{{ route('vendors.categories.unlink') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="vendors_id" value="{{ $vendor->id }}">
                            <input type="hidden" name="vendor_category_id" value="{{ $category->id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Отвязать">
                                <i class="fa fa-unlink"></i>
                            </button>
                        </form>

                        <form class="d-inline-block delete-form ml-lg-2"
                              action="{{ route('vendor.category.delete') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="vendors_id" value="{{ $vendor->id }}">
                            <input type="hidden" name="vendor_category_id" value="{{ $category->id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Отвязать и удалить товары">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>

                    @elseif(!$category->children->count())
                        <a href="{{ route('vendor.category.sync', ['id' => $vendor->id, 'categoryId' => $category->id]) }}"
                           data-toggle="tooltip"
                           title="Синхронизировать" class="btn btn-primary"><i class="fa fa-link"></i></a>
                    @endif
                </div>
            </div>

        </li>

    @else

        <li class="card my-1 pl-1">
            <div class="row">
                <div class="col">

                    @if($vendorSynchronizedCategories->has($category->id))
                        <span class="float-right px-1">
                            <span class="d-none d-lg-inline-block">Локальная категория:
                                <span class="ml-2"> {{ $vendorSynchronizedCategories->get($category->id)->localCategory->name }}</span>
                            </span>
                            <span class="ml-2 float-right px-1">(Продуктов: {{ $vendorSynchronizedCategories->get($category->id)->vendor_products_count }}
                                )</span>
                        </span>
                    @endif

                    <span>{{ $category->name }}</span>
                </div>
                <div class="col-auto">

                    @if($vendorSynchronizedCategories->has($category->id))

                        <a href="{{ route('vendor.category.products.index', ['id' => $vendor->id, 'categoryId' => $category->id]) }}"
                           data-toggle="tooltip"
                           title="Синхронизировать продукты" class="btn btn-primary"><i class="fa fa-list"></i></a>

                        <form class="d-inline-block unlink-form ml-lg-2"
                              action="{{ route('vendor.category.unlink') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="vendors_id" value="{{ $vendor->id }}">
                            <input type="hidden" name="vendor_category_id" value="{{ $category->id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Отвязать">
                                <i class="fa fa-unlink"></i>
                            </button>
                        </form>

                        <form class="d-inline-block delete-form ml-lg-2"
                              action="{{ route('vendor.category.delete') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="vendors_id" value="{{ $vendor->id }}">
                            <input type="hidden" name="vendor_category_id" value="{{ $category->id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Отвязать и удалить товары">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>

                    @elseif(!$category->children->count())
                        <a href="{{ route('vendor.category.sync', ['id' => $vendor->id, 'categoryId' => $category->id]) }}"
                           data-toggle="tooltip"
                           title="Синхронизировать" class="btn btn-primary"><i class="fa fa-link"></i></a>
                    @endif
                </div>
            </div>

        </li>

    @endif

    @if($category->children->count())
        <li class="my-1">
            <div id="category-{{ $category->id }}" class="collapse">
                <ul class="category-list">
                    @include('content.admin.vendors.category.list.parts.category_list', ['categories' => $category->children,])
                </ul>
            </div>
        </li>
    @endif

@endforeach