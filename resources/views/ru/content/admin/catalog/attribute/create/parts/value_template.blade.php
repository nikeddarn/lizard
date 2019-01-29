<div id="attribute-value-template" class="d-none">
    <div class="card p-5 mb-5 attribute-value-item">

        <button type="button" class="btn btn-danger attribute-value-item-delete mr-md-2 mt-md-2" data-toggle="tooltip"
                title="Удалить значение атрибута">
            <i class="fa fa-trash-o"></i>&nbsp;
        </button>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required">Значение (ru)</label>
            </div>
            <div class="col-sm-8">
                <input name="value_ru[]" type="text" required class="value_ru w-100">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required">Значение (ua)</label>
            </div>
            <div class="col-sm-8">
                <input name="value_uk[]" type="text" required class="value_uk w-100">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required">Url</label>
            </div>
            <div class="col-sm-8">
                <input name="url[]" type="text" required class="url w-100" placeholder="Last part of filtered products route">
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