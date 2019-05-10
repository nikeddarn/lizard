<div class="card card-body">

    <div class="d-flex justify-content-between">

        <h1 class="h4 text-gray-hover">{{ $product->name }}</h1>

        <div class="d-inline-flex align-items-start">

            @if($product->published)
                <a href="{{ route('shop.product.index', ['id' => $product->url]) }}" data-toggle="tooltip"
                   title="Смотреть в магазине" class="btn btn-primary mr-1">
                    <i class="svg-icon-larger" data-feather="eye"></i>
                </a>
            @endif

            <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" data-toggle="tooltip"
               title="Редактировать" class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="edit"></i>
            </a>

            <form class="product-delete-form d-inline-block mx-1"
                  action="{{ route('admin.products.destroy', ['id' => $product->id]) }}" method="post">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                    <i class="svg-icon-larger" data-feather="trash-2"></i>
                </button>
            </form>

            <a href="{{ route('admin.products.index') }}" data-toggle="tooltip" title="К списку продуктов"
               class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>

        </div>

    </div>

</div>
