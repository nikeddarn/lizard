<div id="product-image-input-template">

    <div class="card p-5 mb-5 product-image-item d-none">

        <button type="button" class="btn btn-danger product-image-item-delete mr-md-2 mt-md-2" data-toggle="tooltip"
                title="Удалить изображение">
            <i class="fa fa-trash-o"></i>&nbsp;
        </button>

        <div class="row align-items-center">
            <div class="col-lg-3 custom-control custom-radio text-center">
                <div class="image-priority d-none">
                    <input class="custom-control-input" type="radio" name="priority">
                    <label class="custom-control-label">Основное изображение</label>
                </div>
            </div>
            <div class="col-lg-8">
                @include('elements.input_image.index', ['inputFileFieldName' => 'image[]'])
            </div>
        </div>
    </div>

</div>