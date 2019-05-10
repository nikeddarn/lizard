<div class="row">
    <div class="col-sm-10 pr-sm-5">
        <div class="alert alert-info">
            <div class="mb-2">Допустимые ключи:</div>
            <ul class="list-unstyled">
                <li><strong>CATEGORY_NAME</strong> - Название категории</li>
                <li><strong>FILTER_NAME</strong> - Название фильтра</li>
                <li><strong>FILTER_VALUE</strong> - Значение фильтра, связанное с виртуальной категорией</li>
            </ul>
        </div>
    </div>
</div>

<div class="card p-5 mb-5">
    <div class="row form-group">
        <div class="col-sm-2">
            <label for="virtual_category_name_ru">Название категории (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="virtual_category_name_ru" name="virtual_category_name_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('virtual_category_name_ru', $virtualCategorySeoData['ru']['name']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="virtual_category_name_uk">Название категории (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="virtual_category_name_uk" name="virtual_category_name_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('virtual_category_name_uk', $virtualCategorySeoData['uk']['name']) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">
    <div class="row form-group">
        <div class="col-sm-2">
            <label for="virtual_category_title_ru">Seo Title (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="virtual_category_title_ru" name="virtual_category_title_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('virtual_category_title_ru', $virtualCategorySeoData['ru']['title']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="virtual_category_title_uk">Seo Title (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="virtual_category_title_uk" name="virtual_category_title_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('virtual_category_title_uk', $virtualCategorySeoData['uk']['title']) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="virtual_category_description_ru">Seo Description (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="virtual_category_description_ru" name="virtual_category_description_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('virtual_category_description_ru', $virtualCategorySeoData['ru']['description']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="virtual_category_description_uk">Seo Description (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="virtual_category_description_uk" name="virtual_category_description_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('virtual_category_description_uk', $virtualCategorySeoData['uk']['description']) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="virtual_category_keywords_ru">Seo Keywords (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="virtual_category_keywords_ru" name="virtual_category_keywords_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('virtual_category_keywords_ru', $virtualCategorySeoData['ru']['keywords']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="virtual_category_keywords_uk">Seo Keywords (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="virtual_category_keywords_uk" name="virtual_category_keywords_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('virtual_category_keywords_uk', $virtualCategorySeoData['uk']['keywords']) }}</textarea>
        </div>
    </div>

</div>
