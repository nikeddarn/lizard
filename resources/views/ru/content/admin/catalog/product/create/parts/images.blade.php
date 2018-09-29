<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="price1">Изображение</label>
        </div>
        <div class="col-sm-8">
            <div class="input-group image-preview">

                {{-- don't give a name === doesn't send on POST/GET --}}
                <input type="text" class="form-control image-preview-filename" disabled="disabled">

                <div class="input-group-btn">

                    {{-- image-preview-clear button --}}
                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                        <span class="fa fa-remove"></span> Очистить
                    </button>

                    {{-- image-preview-input --}}
                    <div class="btn btn-default image-preview-input">
                        <span class="fa fa-folder-open"></span>
                        <span class="image-preview-input-title">Выбрать</span>
                        <input type="file" name="image[]"/>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="price1">Цена 1</label>
        </div>
        <div class="col-sm-8">
            <div class="input-group image-preview">

                <input type="text" class="form-control image-preview-filename" disabled="disabled">

                <div class="input-group-btn">

                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                        <span class="fa fa-remove"></span> Очистить
                    </button>

                    <div class="btn btn-default image-preview-input">
                        <span class="fa fa-folder-open"></span>
                        <span class="image-preview-input-title">Выбрать</span>
                        <input type="file" name="image[]"/>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>