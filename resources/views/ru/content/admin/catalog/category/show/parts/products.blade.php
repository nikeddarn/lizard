<div class="card py-2 px-1 p-lg-5 mb-5">
    <h4 class="mb-5 text-center">Продукты категории</h4>

    @if($products->count())

        <table class="table">

            <tbody>

            @foreach($products as $product)

                <tr>

                    <td class="d-none d-lg-table-cell">
                        @if($product->primaryImage)
                            <img src="/storage/{{ $product->primaryImage->image }}" class="img-fluid table-image">
                        @endif
                    </td>

                    <td>{{ $product->name }}</td>


                    <td class="text-right">

                        <a href="{{ route('admin.products.show', ['id' => $product->id]) }}" data-toggle="tooltip"
                           title="Просмотреть" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>

                        <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" data-toggle="tooltip"
                           title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                        <form class="d-inline-block product-delete-form"
                              action="{{ route('admin.products.destroy', ['id' => $product->id]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                    title="Удалить продукт">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </form>

                    </td>

                </tr>


            @endforeach

            </tbody>

        </table>

        @if($products->links())
            <div class="col-lg-12 my-4 items-pagination">{{$products->links()}}</div>
        @endif

    @endif

    <div class="col-lg-12 my-4 text-right">
        <a href="{{route('admin.products.create')}}" class="btn btn-primary">
            <i class="fa fa-plus"></i>&nbsp;
            <span>Создать продукт</span>
        </a>
    </div>

</div>
