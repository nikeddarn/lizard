<div class="input-group input-file-block">

    {{-- don't give a name === doesn't send on POST/GET --}}
    <input type="text" class="form-control image-preview-filename" disabled="disabled">

    <span class="input-group-btn">

        {{-- image-preview-clear button --}}
        <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
            <span class="fa fa-remove"></span> Очистить
        </button>

        {{-- image-preview-input --}}
        <div class="btn btn-default image-preview-input">
            <span class="fa fa-folder-open"></span>
            <span class="image-preview-input-title">Выбрать</span>
            <input class="input-image" type="file" name="{{ isset($inputFileFieldName) ? $inputFileFieldName : 'image' }}" accept="image/*"/>
        </div>
    </span>

</div>
