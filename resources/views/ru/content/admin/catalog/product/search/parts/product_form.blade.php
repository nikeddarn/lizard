<div class="table-responsive">

    <table class="table">

        <thead>

        <tr>
            {{-- Created at--}}
            <td>
                <strong>Создан</strong>
            </td>

            {{-- Published--}}
            <td>
                <strong>Опубликован</strong>
            </td>

            {{-- Archived--}}
            <td>
                <strong>Архивный</strong>
            </td>

            <td class="d-none d-lg-table-cell text-center"><strong>Изображение</strong></td>

            {{-- Name--}}
            <td>
                <strong>Наименование</strong>
            </td>

            {{-- Category--}}
            <td>
                <strong>Категории</strong>
            </td>

            {{-- Vendors--}}
            <td>
                <strong>Поставщики</strong>
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
