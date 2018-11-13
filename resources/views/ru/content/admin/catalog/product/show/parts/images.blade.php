<div class="card py-2 px-1 p-lg-5 mb-5">
    <h4 class="mb-5 text-center">Изображения продукта</h4>

    @if($product->productImages->count())

        <table class="table">

            <tbody>

            @foreach($product->productImages as $image)

                <tr>

                    <td>
                        <img src="/storage/{{ $image->small }}" class="img-fluid img-thumbnail table-image">
                    </td>

                    <td class="text-right">

                        @if($image->priority)
                            <span class="badge badge-primary mr-md-4">Основное изображение</span>
                        @else

                            <form class="d-inline-block product-image-priority-form"
                                  action="{{ route('admin.products.image.priority', ['id' => $image->id]) }}"
                                  method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary mr-md-4" data-toggle="tooltip"
                                        title="Сделать изображение продукта основным">Сделать основным
                                </button>
                            </form>

                        @endif

                        <form class="d-inline-block product-image-delete-form"
                              action="{{ route('admin.products.image.destroy', ['id' => $image->id]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                    title="Удалить изображение продукта">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </form>

                    </td>

                </tr>


            @endforeach

            </tbody>

        </table>

    @endif

    <div class="col-lg-12 my-4 text-right">
        <a href="{{route('admin.products.image.create', ['id' => $product->id])}}" class="btn btn-primary">
            <i class="fa fa-plus"></i>&nbsp;
            <span>Добавить изображение продукта</span>
        </a>
    </div>

</div>
