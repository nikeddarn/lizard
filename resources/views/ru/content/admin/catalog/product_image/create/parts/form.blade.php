<form id="product-image-form" method="post" action="{{ route('admin.products.image.store') }}" role="form"
      enctype="multipart/form-data">

    @csrf

    <input type="hidden" name="products_id" value="{{ $product->id }}">

    <div class="card  p-5 mb-5">
        @include('elements.input_image.index')
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-primary">Сохранить изображение</button>
    </div>

</form>
