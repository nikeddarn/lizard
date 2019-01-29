<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ru">Название (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ru" name="name_ru" type="text" required class="w-100" value="{{ old('name_ru', $category->name_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_uk">Название (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_uk" name="name_uk" type="text" required class="w-100" value="{{ old('name_uk', $category->name_uk) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="url">URL</label>
        </div>
        <div class="col-sm-8">
            <input id="url" name="url" type="text" required class="w-100" placeholder="site.name/categories/your-entering-url" value="{{ old('url', $category->url) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="parent_id">Родитель</label>
        </div>
        <div class="col-sm-8">
            <select id="parent_id" name="parent_id" class="selectpicker w-100">
                <option value="0" {{ old('parent_id', $category->parent_id) == "0" ? 'selected="selected"' : ''}}>Корневая категория</option>
                @include('content.admin.catalog.category.update.parts.select_category_options')
            </select>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ru">Изображение</label>
        </div>
        <div class="col-sm-8">
            @include('elements.input_image.index')
        </div>
    </div>

</div>