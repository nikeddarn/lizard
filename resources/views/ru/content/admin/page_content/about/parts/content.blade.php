<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ru">Заголовок (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ru" name="name_ru" type="text" required class="w-100" value="{{ old('name_ru', $pageData ? $pageData->name_ru : '') }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_uk">Заголовок (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_uk" name="name_uk" type="text" required class="w-100" value="{{ old('name_uk', $pageData ? $pageData->name_ru : '') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="content_ru">Описание (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="content_ru" name="content_ru" type="text" class="w-100 summernote" rows="10">{{ old('content_ru', $pageData ? $pageData->content_ru : '') }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="content_uk">Описание (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="content_uk" name="content_uk" type="text" class="w-100 summernote" rows="10">{{ old('content_uk', $pageData ? $pageData->content_uk : '') }}</textarea>
        </div>
    </div>

</div>
