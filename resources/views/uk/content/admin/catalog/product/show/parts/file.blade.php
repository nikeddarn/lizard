@if($product->productFiles->count())
    <div class="row">
        @foreach($product->productFiles as $file)

            <div class="col-6 col-md-3">
                <div class="card card-body p-5">
                    <div class="slider-item">
                        <div class="slider-item-control">
                            <div class="d-flex justify-content-end align-items-start">

                                <form class="product-file-delete-form"
                                      action="{{ route('admin.products.file.destroy', ['id' => $file->id]) }}"
                                      method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ml-1" data-toggle="tooltip"
                                            title="Удалить файл продукта">
                                        <i class="svg-icon-larger" data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <a href="{{ '/storage/' . $file->url }}" class="h6 text-lizard mr-4" title="Скачать">
                            <i class="svg-icon-larger" data-feather="file"></i>
                            <span>{{ $file->name_ru }}</span>
                        </a>

                    </div>
                </div>
            </div>

        @endforeach
    </div>

@endif

<div class="my-5 text-right">
    <a href="{{route('admin.products.file.create', ['id' => $product->id])}}" class="btn btn-primary">
        <i class="svg-icon-larger" data-feather="plus"></i>
        <span>Добавить файл продукта</span>
    </a>
</div>
