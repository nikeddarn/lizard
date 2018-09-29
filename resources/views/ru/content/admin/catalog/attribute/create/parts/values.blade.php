<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ru">Значение (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ru" name="name_ru" type="text" required class="w-100" value="{{ old('name_ru') }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ru">Значение (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ru" name="name_ru" type="text" required class="w-100" value="{{ old('name_ru') }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ru">Изображение</label>
        </div>
        <div class="col-sm-8">
            @include('elements.input_image.index')
        </div>
    </div>

</div>