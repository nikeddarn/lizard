<div class="card p-5 mb-5">

    <div class="alert alert-info mb-5">Используйте абсолютный путь от корня сайта</div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="url_ru">Ссылка (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="url_ru" name="url_ru" type="text" class="w-100" value="{{ old('url_ru') }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="url_uk">Ссылка (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="url_uk" name="url_uk" type="text" class="w-100" value="{{ old('url_uk') }}">
        </div>
    </div>

</div>
