<form id="import-file-form" method="post" action="{{ route('admin.import.apacer') }}" role="form"
      enctype="multipart/form-data">
    @csrf

    <div class="card  p-5 mb-5">
        <div class="custom-file">
            <input type="file" name="source_file" class="custom-file-input" id="product_file">
            <label class="custom-file-label" for="product_file">Выберите файл</label>
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-primary">Импортировать продукты</button>
    </div>

</form>
