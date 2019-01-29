<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="content_ru">Описание (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="content_ru" name="content_ru" type="text" class="w-100 summernote" rows="10">{{ old('content_ru', $category->content_ru) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="content_uk">Описание (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="content_uk" name="content_uk" type="text" class="w-100 summernote" rows="10">{{ old('content_uk', $category->content_uk) }}</textarea>
        </div>
    </div>

</div>