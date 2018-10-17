<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="title_ru">Seo Title (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="title_ru" name="title_ru" type="text" required class="w-100" value="{{ old('title_ru', $category->title_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="title_ua">Seo Title (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="title_ua" name="title_ua" type="text" required class="w-100" value="{{ old('title_ua', $category->title_ua) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="description_ru">Seo Description (ru)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="description_ru" name="description_ru" type="text" required class="w-100" rows="3">{{ old('description_ru', $category->description_ru) }}</textarea>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="description_ua">Seo Description (ua)</label>
        </div>
        <div class="col-sm-8">
            <textarea id="description_ua" name="description_ua" type="text" required class="w-100" rows="3">{{ old('description_ua', $category->description_ua) }}</textarea>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="keywords_ru">Seo Keywords (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="keywords_ru" name="keywords_ru" type="text" class="w-100" placeholder="comma separated keywords (,)" value="{{ old('keywords_ru', $category->keywords_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="keywords_ua">Seo Keywords (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="keywords_ua" name="keywords_ua" type="text" class="w-100" placeholder="comma separated keywords (,)" value="{{ old('keywords_ua', $category->keywords_ua) }}">
        </div>
    </div>

</div>