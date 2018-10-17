<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ru">Название (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ru" name="name_ru" type="text" required class="w-100" value="{{ old('name_ru') }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ua">Название (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ua" name="name_ua" type="text" required class="w-100" value="{{ old('name_ua') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="url">URL</label>
        </div>
        <div class="col-sm-8">
            <input id="url" name="url" type="text" required class="w-100" placeholder="site.name/products/your-entering-url" value="{{ old('url') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="categories_id">Категория</label>
        </div>
        <div class="col-sm-8">
            <select id="categories_id" name="categories_id" class="selectpicker w-100">
                @include('content.admin.catalog.product.create.parts.select_category_options')
            </select>
        </div>
    </div>

</div>