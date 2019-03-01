<div class="table-responsive">

    <table class="table">

        <thead>
        <tr class="text-center">
            <td><strong>Создан</strong></td>
            <td><strong>Архивный</strong></td>
            <td class="d-none d-lg-table-cell"><strong>Изображение</strong></td>
            <td><strong>Название</strong></td>
            <td><strong>Размещен в категориях</strong></td>
            <td><strong>Поставщики</strong></td>
            <td></td>
        </tr>
        </thead>

        <tbody>


        @foreach($products as $product)

            <tr class="text-center">

                <td>{{ $product->created_at->format('d - m - Y') }}</td>

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

                <td>{{ $product->name }}</td>

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

                    <div class="d-flex justify-content-center align-items-start">
                        <a href="{{ route('admin.products.show', ['id' => $product->id]) }}" data-toggle="tooltip"
                           title="Просмотреть" class="btn btn-primary">
                            <i class="svg-icon-larger" data-feather="eye"></i>
                        </a>

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
