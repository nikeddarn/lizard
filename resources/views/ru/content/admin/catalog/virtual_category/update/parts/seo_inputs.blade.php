<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="title_ru">Seo Title (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="title_ru" name="title_ru" type="text" class="w-100" value="{{ old('title_ru', $virtualCategory->title_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="title_uk">Seo Title (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="title_uk" name="title_uk" type="text" class="w-100" value="{{ old('title_uk', $virtualCategory->title_uk) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="description_ru">Seo Description (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="description_ru" name="description_ru" type="text" class="w-100" rows="3">{{ old('description_ru', $virtualCategory->description_ru) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="description_uk">Seo Description (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="description_uk" name="description_uk" type="text" class="w-100" rows="3">{{ old('description_uk', $virtualCategory->description_uk) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="keywords_ru">Seo Keywords (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="keywords_ru" name="keywords_ru" type="text" class="w-100" placeholder="comma separated keywords (,)" value="{{ old('keywords_ru', $virtualCategory->keywords_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="keywords_uk">Seo Keywords (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="keywords_uk" name="keywords_uk" type="text" class="w-100" placeholder="comma separated keywords (,)" value="{{ old('keywords_uk', $virtualCategory->keywords_uk) }}">
        </div>
    </div>

</div>
