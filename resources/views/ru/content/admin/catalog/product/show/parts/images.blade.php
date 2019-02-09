@if($product->productImages->count())

    <div class="table-responsive">

        <table class="table">

            <tbody>

            @foreach($product->productImages as $image)

                <tr>

                    <td>
                        <img src="/storage/{{ $image->medium }}" class="img-fluid">
                    </td>

                    <td class="d-flex align-items-start justify-content-end">

                        @if($image->priority)
                            <span class="btn btn-success" data-toggle="tooltip"
                                    title="Основное изображение">
                                <i class="svg-icon-larger" data-feather="anchor"></i>
                            </span>
                        @else
                            <form class="product-image-priority-form"
                                  action="{{ route('admin.products.image.priority', ['id' => $image->id]) }}"
                                  method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                        title="Сделать основным">
                                    <i class="svg-icon-larger" data-feather="anchor"></i>
                                </button>
                            </form>
                        @endif

                        <form class="product-image-delete-form"
                              action="{{ route('admin.products.image.destroy', ['id' => $image->id]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger ml-1" data-toggle="tooltip"
                                    title="Удалить изображение продукта">
                                <i class="svg-icon-larger" data-feather="trash-2"></i>
                            </button>
                        </form>

                    </td>

                </tr>


            @endforeach

            </tbody>

        </table>

    </div>

@endif

<div class="col-lg-12 my-4 text-right">
    <a href="{{route('admin.products.image.create', ['id' => $product->id])}}" class="btn btn-primary">
        <i class="fa fa-plus"></i>&nbsp;
        <span>Добавить изображение продукта</span>
    </a>
</div>
