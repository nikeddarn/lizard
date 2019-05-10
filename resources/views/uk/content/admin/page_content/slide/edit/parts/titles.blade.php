<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="name_ru">Заголовок (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ru" name="name_ru" type="text" class="w-100" value="{{ old('name_ru', $slide->name_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="name_uk">Заголовок (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_uk" name="name_uk" type="text" class="w-100" value="{{ old('name_uk', $slide->name_uk) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="text_ru">Описание (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="text_ru" name="text_ru" type="text" class="w-100" rows="3">{{ old('text_ru', $slide->text_ru) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="text_uk">Описание (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="text_uk" name="text_uk" type="text" class="w-100" rows="3">{{ old('text_uk', $slide->text_uk) }}</textarea>
        </div>
    </div>

</div>
