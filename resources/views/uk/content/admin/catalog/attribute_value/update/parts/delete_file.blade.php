@if($attributeValue->image)
    <button id="attribute-value-image-delete" type="button" class="btn btn-danger"
            data-toggle="tooltip" title="Удалить изображение">
        <i class="fa fa-trash-o"></i>
    </button>
@else
    <button id="attribute-value-image-delete" type="button" class="btn btn-danger d-none"
            data-toggle="tooltip" title="Удалить изображение">
        <i class="fa fa-trash-o"></i>
    </button>
@endif

<input id="delete-image-input" type="hidden" name="delete-image" value="0">