<div class="row">
    <div class="col-sm-10 pr-sm-5">
        <div class="alert alert-info">
            <div class="mb-2">Допустимые ключи:</div>
            <ul class="list-unstyled">
                <li><strong>PRODUCT_NAME</strong> - Название продукта</li>
                <li><strong>ATTRIBUTES_WITH_VALUES</strong> - Названия аттрибута : значение аттрибутов, разделенные запятой</li>
                <li><strong>[*PRODUCT_MODEL*]</strong> - Модель продукта (если присутствует)</li>
                <li><strong>[*PRODUCT_ARTICUL*]</strong> - Артикул продукта (если присутствует)</li>
                <li><strong>[*PRODUCT_CODE*]</strong> - Код продукта (если присутствует)</li>
                <li><strong>[*PRODUCT_MANUFACTURER*]</strong> - Страна-производитель продукта (если присутствует)</li>
                <li><strong>[*PRODUCT_PRICE*]</strong> - Розничная цена продукта (форматированное число) (если присутствует)</li>
                <li><strong>[*PRODUCT_WARRANTY*]</strong> - Гарантия на продукт, месяцы (целое число) (если присутствует)</li>
                <li><strong>*</strong> - Любая подстрока</li>
            </ul>
        </div>
    </div>
</div>

<div class="card p-5 mb-5">
    <div class="row form-group">
        <div class="col-sm-2">
            <label for="product_title_ru">Seo Title (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="product_title_ru" name="product_title_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('product_title_ru', $productSeoData['ru']['title']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="product_title_uk">Seo Title (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="product_title_uk" name="product_title_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('product_title_uk', $productSeoData['uk']['title']) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="product_description_ru">Seo Description (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="product_description_ru" name="product_description_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('product_description_ru', $productSeoData['ru']['description']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="product_description_uk">Seo Description (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="product_description_uk" name="product_description_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('product_description_uk', $productSeoData['uk']['description']) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="product_keywords_ru">Seo Keywords (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="product_keywords_ru" name="product_keywords_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('product_keywords_ru', $productSeoData['ru']['keywords']) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="product_keywords_uk">Seo Keywords (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="product_keywords_uk" name="product_keywords_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('product_keywords_uk', $productSeoData['uk']['keywords']) }}</textarea>
        </div>
    </div>

</div>
