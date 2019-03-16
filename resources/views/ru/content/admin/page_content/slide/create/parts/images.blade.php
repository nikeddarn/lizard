<div class="card card-body">

    <div class="row mb-4">
        <div class="col-sm-2">
            <label for="image_ru">Изображение (RU)</label>
        </div>
        <div class="col-sm-8">
            @include('elements.input_image.index', ['inputFileFieldName' => 'image_ru'])
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            <label for="image_uk">Изображение (UA)</label>
        </div>
        <div class="col-sm-8">
            @include('elements.input_image.index', ['inputFileFieldName' => 'image_uk'])
        </div>
    </div>

</div>
