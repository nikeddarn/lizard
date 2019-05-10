@if($product->productImages->count())
    <div class="row">
        @foreach($product->productImages as $image)

            <div class="col-6 col-md-3">
                <div class="card card-body">
                    <div class="slider-item">
                        <div class="slider-item-control">
                            <div class="d-flex justify-content-end align-items-start">
                                @if($image->priority)
                                    <span class="btn btn-success" data-toggle="tooltip"
                                          title="Основное изображение">
                                <i class="svg-icon-larger" data-feather="check"></i>
                            </span>
                                @else
                                    <form class="product-image-priority-form"
                                          action="{{ route('admin.products.image.priority', ['id' => $image->id]) }}"
                                          method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" data-toggle="tooltip"
                                                title="Сделать основным">
                                            <i class="svg-icon-larger" data-feather="check"></i>
                                        </button>
                                    </form>
                                @endif

                                <form class="product-image-delete-form"
                                      action="{{ route('admin.products.image.destroy', ['id' => $image->id]) }}"
                                      method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ml-1" data-toggle="tooltip"
                                            title="Удалить изображение продукта">
                                        <i class="svg-icon-larger" data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <img src="/storage/{{ $image->medium }}" class="img-fluid">
                    </div>
                </div>
            </div>

        @endforeach
    </div>

@endif

<div class="my-5 text-right">
    <a href="{{route('admin.products.image.create', ['id' => $product->id])}}" class="btn btn-primary">
        <i class="svg-icon-larger" data-feather="plus"></i>
        <span>Добавить изображение продукта</span>
    </a>
</div>
