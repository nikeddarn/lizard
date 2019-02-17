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
            <label for="leaf_category_title_ru">Seo Title (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="leaf_category_title_ru" name="leaf_category_title_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('leaf_category_title_ru', $leafCategorySeoData['ru']['title']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="leaf_category_title_uk">Seo Title (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="leaf_category_title_uk" name="leaf_category_title_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('leaf_category_title_uk', $leafCategorySeoData['uk']['title']) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="leaf_category_description_ru">Seo Description (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="leaf_category_description_ru" name="leaf_category_description_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('leaf_category_description_ru', $leafCategorySeoData['ru']['description']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="leaf_category_description_uk">Seo Description (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="leaf_category_description_uk" name="leaf_category_description_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('leaf_category_description_uk', $leafCategorySeoData['uk']['description']) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="leaf_category_keywords_ru">Seo Keywords (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="leaf_category_keywords_ru" name="leaf_category_keywords_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('leaf_category_keywords_ru', $leafCategorySeoData['ru']['keywords']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="leaf_category_keywords_uk">Seo Keywords (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="leaf_category_keywords_uk" name="leaf_category_keywords_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('leaf_category_keywords_uk', $leafCategorySeoData['uk']['keywords']) }}</textarea>
        </div>
    </div>

</div>
