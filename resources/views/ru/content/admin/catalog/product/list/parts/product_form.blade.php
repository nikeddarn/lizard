@if($products->count())

    <table class="table">

        <thead>
        <tr class="text-center">
            <td class="d-none d-lg-table-cell"><strong>Изображение</strong></td>
            <td class="d-none d-lg-table-cell"><strong>Id</strong></td>
            <td><strong>Название</strong></td>
            <td><strong>Размещен в категориях</strong></td>
            <td><strong>Поставщики</strong></td>
            <td></td>
        </tr>
        </thead>

        <tbody>


        @foreach($products as $product)

            <tr class="text-center">

                <td class="d-none d-lg-table-cell">
                    @if($product->primaryImage)
                        <img src="/storage/{{ $product->primaryImage->small }}" class="img-responsive table-image">
                    @endif
                </td>

                <td class="d-none d-lg-table-cell">{{ $product->id }}</td>

                <td>{{ $product->name }}</td>

                <td>
                    @foreach($product->categories as $category)
                        <a class="d-block" href="{{ route('admin.categories.show', ['id' => $category->id]) }}">{{ $category->name }}</a>
                    @endforeach
                </td>

                <td>
                    @foreach($product->vendors as $vendor)
                        <div>{{ $vendor->name_ru }}</div>
                    @endforeach
                </td>

                <td class="text-center">

                    <a href="{{ route('admin.products.show', ['id' => $product->id]) }}" data-toggle="tooltip"
                       title="Просмотреть" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>

                    <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" data-toggle="tooltip"
                       title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                    <form class="product-delete-form d-inline-block ml-lg-2"
                          action="{{ route('admin.products.destroy', ['id' => $product->id]) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </form>

                </td>

            </tr>

        @endforeach

        </tbody>
    </table>

@else

    <p>Нет ни одного продукта</p>

@endif

@if($products->links())
    <div class="col-lg-12 my-4">{{$products->links()}}</div>
@endif
