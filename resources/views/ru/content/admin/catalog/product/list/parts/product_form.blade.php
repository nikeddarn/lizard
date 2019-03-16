<div class="table-responsive">

    <table class="table">

        <thead>

        <tr>
            {{-- Created at--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(!request()->query('sortBy') || request()->query('sortBy') === 'createdAt')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="createdAtDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Создан</span>
                                @if(!request()->query('sortMethod') || request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="createdAtDropdown">
                                @if(!request()->query('sortMethod') || request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'createdAt', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'createdAt', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="createdAtDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Создан</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="createdAtDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'createdAt', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'createdAt', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Published--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'published')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="publishedDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Опубликован</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="publishedDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">Опубликованные в начале</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'published', 'sortMethod' => 'desc']), ['page' => ''])) }}">Опубликованные
                                        в конце</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'published', 'sortMethod' => 'asc']), ['page' => ''])) }}">Опубликованные
                                        в начале</a>
                                    <span class="dropdown-item disabled cursor-pointer">Опубликованные в конце</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="publishedDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Опубликован</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="publishedDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'published', 'sortMethod' => 'asc']), ['page' => ''])) }}">Опубликованные
                                    в начале</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'published', 'sortMethod' => 'desc']), ['page' => ''])) }}">Опубликованные
                                    в конце</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Archived--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'archived')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="archivedDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Архивный</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="archivedDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item disabled cursor-pointer">Архивные в начале</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'archived', 'sortMethod' => 'desc']), ['page' => ''])) }}">Архивные
                                        в конце</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'archived', 'sortMethod' => 'asc']), ['page' => ''])) }}">Архивные
                                        в начале</a>
                                    <span class="dropdown-item disabled cursor-pointer">Архивные в конце</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="archivedDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Архивный</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="archivedDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'archived', 'sortMethod' => 'asc']), ['page' => ''])) }}">Архивные
                                    в начале</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'archived', 'sortMethod' => 'desc']), ['page' => ''])) }}">Архивные
                                    в конце</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            <td class="d-none d-lg-table-cell text-center"><strong>Изображение</strong></td>

            {{-- Name--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'name')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="nameDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Наименование</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="nameDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'name', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'name', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="nameDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Наименование</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="nameDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'name', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'name', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Category--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'category')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="categoryDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Категории</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="categoryDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'category', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'category', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="categoryDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Категории</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="categoryDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'category', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'category', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            {{-- Name--}}
            <td>
                <div class="d-flex justify-content-around align-items-start">
                    @if(request()->query('sortBy') === 'vendor')
                        <div class="dropdown dropdown-hover">
                            <button class="btn dropdown-toggle btn-primary caret-off" type="button"
                                    id="vendorDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Поставщики</span>
                                @if(request()->query('sortMethod') === 'asc')
                                    <i class="svg-icon-larger" data-feather="chevron-up"></i>
                                @else
                                    <i class="svg-icon-larger" data-feather="chevron-down"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu m-0" aria-labelledby="vendorDropdown">
                                @if(request()->query('sortMethod') === 'asc')
                                    <span class="dropdown-item cursor-pointer disabled">По возрастанию</span>
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'vendor', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                        убыванию</a>
                                @elseif(request()->query('sortMethod') === 'desc')
                                    <a class="dropdown-item"
                                       href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'vendor', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                        возрастанию</a>
                                    <span class="dropdown-item disabled cursor-pointer">По убыванию</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="dropdown dropdown-hover position-relative py-1">
                            <strong class="dropdown-toggle cursor-pointer" id="vendorDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Поставщики</span>
                            </strong>
                            <div class="dropdown-menu m-0" aria-labelledby="vendorDropdown">
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'vendor', 'sortMethod' => 'asc']), ['page' => ''])) }}">По
                                    возрастанию</a>
                                <a class="dropdown-item"
                                   href="{{ route(request()->route()->getName(), array_diff_key(array_merge(request()->route()->parameters(), request()->query(), ['sortBy' => 'vendor', 'sortMethod' => 'desc']), ['page' => ''])) }}">По
                                    убыванию</a>
                            </div>
                        </div>
                    @endif
                </div>
            </td>

            <td></td>

        </tr>

        </thead>

        <tbody>


        @foreach($products as $product)

            <tr class="text-center">

                <td>{{ $product->created_at->format('d - m - Y') }}</td>

                <td>
                    @if($product->published)
                        <i class="svg-icon-larger text-success" data-feather="eye"></i>
                    @else
                        <i class="svg-icon-larger text-danger" data-feather="eye"></i>
                    @endif
                </td>

                <td>
                    @if($product->is_archive)
                        <i class="svg-icon-larger text-danger" data-feather="archive"></i>
                    @endif
                </td>

                <td class="d-none d-lg-table-cell">
                    @if($product->primaryImage)
                        <img src="/storage/{{ $product->primaryImage->small }}" class="img-responsive table-image">
                    @endif
                </td>

                <td>
                    @if($product->published)
                        <a href="{{ url(route('shop.product.index', ['url' => $product->url])) }}">{{ $product->name }}</a>
                    @else
                        <span>{{ $product->name }}</span>
                    @endif
                </td>

                <td>
                    @foreach($product->categories as $category)
                        <a class="d-block"
                           href="{{ route('admin.categories.show', ['id' => $category->id]) }}">{{ $category->name }}</a>
                    @endforeach
                </td>

                <td>
                    @foreach($product->vendors as $vendor)
                        <div>{{ $vendor->name_ru }}</div>
                    @endforeach
                </td>

                <td>

                    <div class="product-actions d-flex justify-content-center align-items-start">

                        <a href="{{ route('admin.products.show', ['id' => $product->id]) }}" data-toggle="tooltip"
                           title="Просмотреть" class="btn btn-primary">
                            <i class="svg-icon-larger" data-feather="eye"></i>
                        </a>

                        <form
                            class="products-publish-off-form ml-1 {{ $product->published ? 'd-inline-block' : 'd-none' }}"
                            action="{{ route('admin.products.publish.off') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                    title="Выключить публикацию продукта">
                                <i class="svg-icon-larger" data-feather="check-circle"></i>
                            </button>
                        </form>

                        <form
                            class="products-publish-on-form ml-1 {{ $product->published ? 'd-none' : 'd-inline-block' }}"
                            action="{{ route('admin.products.publish.on') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-primary btn-outline-theme" data-toggle="tooltip"
                                    title="Включить публикацию продукта">
                                <i class="svg-icon-larger" data-feather="check-circle"></i>
                            </button>
                        </form>

                        <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" data-toggle="tooltip"
                           title="Редактировать" class="btn btn-primary mx-1">
                            <i class="svg-icon-larger" data-feather="edit"></i>
                        </a>

                        <form class="product-delete-form d-inline-block"
                              action="{{ route('admin.products.destroy', ['id' => $product->id]) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                                <i class="svg-icon-larger" data-feather="trash-2"></i>
                            </button>
                        </form>
                    </div>

                </td>

            </tr>

        @endforeach

        </tbody>
    </table>

</div>
