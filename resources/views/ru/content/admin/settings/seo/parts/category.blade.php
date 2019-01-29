<div class="row">
    <div class="col-sm-10 pr-sm-5">
        <div class="alert alert-info">
            <div class="mb-2">Допустимые ключи:</div>
            <ul class="list-unstyled">
                <li><strong>CATEGORY_NAME</strong> - Название категории</li>
            </ul>
        </div>
    </div>
</div>

<div class="card p-5 mb-5">
    <div class="row form-group">
        <div class="col-sm-2">
            <label for="category_title_ru">Seo Title (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="category_title_ru" name="category_title_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('category_title_ru', $categorySeoData['ru']['title']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="category_title_uk">Seo Title (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="category_title_uk" name="category_title_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('category_title_uk', $categorySeoData['uk']['title']) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="category_description_ru">Seo Description (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="category_description_ru" name="category_description_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('category_description_ru', $categorySeoData['ru']['description']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="category_description_uk">Seo Description (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="category_description_uk" name="category_description_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('category_description_uk', $categorySeoData['uk']['description']) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="category_keywords_ru">Seo Keywords (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="category_keywords_ru" name="category_keywords_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('category_keywords_ru', $categorySeoData['ru']['keywords']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="category_keywords_uk">Seo Keywords (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="category_keywords_uk" name="category_keywords_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('category_keywords_uk', $categorySeoData['uk']['keywords']) }}</textarea>
        </div>
    </div>

</div>
