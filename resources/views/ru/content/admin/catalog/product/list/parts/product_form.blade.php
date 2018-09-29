<table id="categories-list-table" class="table table-bordered">

    <thead>
    <tr>
        <td colspan="4"><strong>Список продуктов</strong></td>
    </tr>

    </thead>

    <tbody>

    @if($products->count())

        @foreach($products as $product)

            <tr>

                <td><img src="{{ $product->image }}" class="img-responsive"></td>

                <td>{{ $product->name }}</td>

                <td>{{ $product->category->name }}</td>

                <td class="category-control-cell text-center">

                    <a href="{{ route('admin.products.show', ['id' => $product->id]) }}" data-toggle="tooltip"
                       title="Просмотреть" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>

                    <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" data-toggle="tooltip"
                       title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                    <form class="category-form ml-2" action="{{ route('admin.products.destroy', ['id' => $product->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </form>

                </td>

            </tr>

        @endforeach

    @else

        <tr>
            <td colspan="4">Нет ни одного продукта</td>
        </tr>

    @endif

    </tbody>
</table>