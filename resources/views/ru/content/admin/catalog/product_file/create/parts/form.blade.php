<form id="product-video-form" method="post" action="{{ route('admin.products.file.store') }}" role="form"
      enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="products_id" value="{{ $product->id }}">

    <div class="card p-5 mb-5">

        <div class="row">
            <div class="col-lg-6">

                <div class="mb-4">
                    <label for="product_file_name_ru">Название (RU)</label>
                    <input id="product_file_name_ru" class="w-100" type="text" name="product_file_name_ru" value="{{ old('product_file_name_ru') }}">
                </div>

                <div class="mb-4">
                    <label for="product_file_name_uk">Название (UA)</label>
                    <input id="product_file_name_uk" class="w-100" type="text" name="product_file_name_uk" value="{{ old('product_file_name_uk') }}">
                </div>

                <div class="custom-file">
                    <input type="file" name="product_file" class="custom-file-input" id="product_file">
                    <label class="custom-file-label" for="product_file">Выберите файл</label>
                </div>

            </div>
        </div>

    </div>


    <div class="text-right">
        <button type="submit" class="btn btn-primary">Сохранить файл</button>
    </div>

</form>
