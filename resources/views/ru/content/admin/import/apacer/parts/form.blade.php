<form id="import-file-form" method="post" action="{{ route('admin.import.apacer') }}" role="form"
      enctype="multipart/form-data">
    @csrf

    <div class="card  p-5 mb-5">
        <div class="custom-file">
            <input type="file" name="price_list" class="custom-file-input" id="price_list">
            <label class="custom-file-label" for="price_list">Прайс лист</label>
        </div>
    </div>

    <div class="card  p-5 mb-5">
        <div class="custom-file">
            <input type="file" name="specification" class="custom-file-input" id="specification">
            <label class="custom-file-label" for="specification">Спецификация</label>
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-primary">Импортировать продукты</button>
    </div>

</form>
