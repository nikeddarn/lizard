<form id="main-content-form" method="post"
      action="{{ route('admin.content.main.update.seo') }}"
      role="form"
      enctype="multipart/form-data">
    @csrf

    <div class="card p-5 mb-5">
        <div class="row form-group">
            <div class="col-md-4 col-lg-2">
                <label for="title_ru">Seo Title (ru)</label>
            </div>
            <div class="col-md-8">
            <textarea id="title_ru" name="title_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('title_ru', $pageData['title_ru']) }}</textarea>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-md-4 col-lg-2">
                <label for="title_uk">Seo Title (ua)</label>
            </div>
            <div class="col-md-8">
            <textarea id="title_uk" name="title_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('title_ru', $pageData['title_uk']) }}</textarea>
            </div>
        </div>

    </div>

    <div class="card p-5 mb-5">

        <div class="row form-group">
            <div class="col-md-4 col-lg-2">
                <label for="description_ru">Seo Description (ru)</label>
            </div>
            <div class="col-md-8">
            <textarea id="description_ru" name="description_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('description_ru', $pageData['description_ru']) }}</textarea>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-md-4 col-lg-2">
                <label for="description_uk">Seo Description (ua)</label>
            </div>
            <div class="col-md-8">
            <textarea id="description_uk" name="description_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('description_uk', $pageData['description_uk']) }}</textarea>
            </div>
        </div>

    </div>

    <div class="card p-5 mb-5">

        <div class="row form-group">
            <div class="col-md-4 col-lg-2">
                <label for="keywords_ru">Seo Keywords (ru)</label>
            </div>
            <div class="col-md-8">
            <textarea id="keywords_ru" name="keywords_ru" type="text" class="w-100 p-1"
                      rows="3">{{ old('keywords_ru', $pageData['keywords_ru']) }}</textarea>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-md-4 col-lg-2">
                <label for="keywords_uk">Seo Keywords (ua)</label>
            </div>
            <div class="col-md-8">
            <textarea id="keywords_uk" name="keywords_uk" type="text" class="w-100 p-1"
                      rows="3">{{ old('keywords_uk', $pageData['keywords_uk']) }}</textarea>
            </div>
        </div>

    </div>


    <button type="submit" class="btn btn-primary">Сохранить изменения</button>

</form>
