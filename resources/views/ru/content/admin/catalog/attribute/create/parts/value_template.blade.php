<div id="attribute-value-template" class="d-none">
    <div class="card p-5 mb-5 attribute-value-item">

        <button type="button" class="btn btn-danger attribute-value-item-delete mr-md-2 mt-md-2" data-toggle="tooltip"
                title="Удалить значение атрибута">
            <i class="fa fa-trash-o"></i>&nbsp;
        </button>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="value_ru">Значение (ru)</label>
            </div>
            <div class="col-sm-8">
                <input id="value_ru" name="value_ru[]" type="text" required class="w-100">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="value_ua">Значение (ua)</label>
            </div>
            <div class="col-sm-8">
                <input id="value_ua" name="value_ua[]" type="text" required class="w-100">
            </div>
        </div>

        <div class="row form-group">

            <div class="col-sm-2">
                <label for="name_ru">Изображение</label>
            </div>

            <div class="col-sm-8">

                @include('elements.input_image.index', ['inputFileFieldName' => 'image[]'])

            </div>

        </div>

    </div>
</div>