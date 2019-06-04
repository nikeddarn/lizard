<form id="import-file-form" method="post" action="{{ route('admin.export.hotline.categories') }}" role="form"
      enctype="multipart/form-data">
    @csrf

    <div class="card  p-5 mb-5">
        <div class="custom-file">
            <input type="file" name="categories" class="custom-file-input" id="price_list">
            <label class="custom-file-label" for="price_list">Файл категорий Hotline (.csv)</label>
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-primary">Загрузить категории</button>
    </div>

</form>
