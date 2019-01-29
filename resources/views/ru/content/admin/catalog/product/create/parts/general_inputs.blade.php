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
            <label class="required" for="name_uk">Название (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_uk" name="name_uk" type="text" required class="w-100" value="{{ old('name_uk') }}">
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
            <label for="model_ru">Модель (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="model_ru" name="model_ru" type="text" class="w-100" value="{{ old('model_ru') }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="model_uk">Модель (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="model_uk" name="model_uk" type="text" class="w-100" value="{{ old('model_uk') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="articul">Артикул товара</label>
        </div>
        <div class="col-sm-8">
            <input id="articul" name="articul" type="text" class="w-100" value="{{ old('articul') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="code">Код товара</label>
        </div>
        <div class="col-sm-8">
            <input id="code" name="code" type="text" class="w-100" value="{{ old('code') }}">
        </div>
    </div>

</div>


<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="manufacturer_ru">Страна производитель (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="manufacturer_ru" name="manufacturer_ru" type="text" class="w-100" value="{{ old('manufacturer_ru') }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="manufacturer_uk">Страна производитель (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="manufacturer_uk" name="manufacturer_uk" type="text" class="w-100" value="{{ old('manufacturer_uk') }}">
        </div>
    </div>

</div>