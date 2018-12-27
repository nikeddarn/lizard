<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ru">Название (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ru" name="name_ru" type="text" required class="w-100"
                   value="{{ old('name_ru', $product->name_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ua">Название (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ua" name="name_ua" type="text" required class="w-100"
                   value="{{ old('name_ua', $product->name_ua) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="url">URL</label>
        </div>
        <div class="col-sm-8">
            <input id="url" name="url" type="text" required class="w-100"
                   placeholder="site.name/products/your-entering-url" value="{{ old('url', $product->url) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="model_ru">Модель (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="model_ru" name="model_ru" type="text" class="w-100" value="{{ old('model_ru', $product->model_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="model_ua">Модель (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="model_ua" name="model_ua" type="text" class="w-100" value="{{ old('model_ua', $product->model_ua) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="articul">Артикул товара</label>
        </div>
        <div class="col-sm-8">
            <input id="articul" name="articul" type="text" class="w-100" value="{{ old('articul', $product->articul) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="code">Код товара</label>
        </div>
        <div class="col-sm-8">
            <input id="code" name="code" type="text" class="w-100" value="{{ old('code', $product->code) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="manufacturer_ru">Страна производитель (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="manufacturer_ru" name="manufacturer_ru" type="text" class="w-100"
                   value="{{ old('manufacturer_ru', $product->manufacturer_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="manufacturer_ua">Страна производитель (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="manufacturer_ua" name="manufacturer_ua" type="text" class="w-100"
                   value="{{ old('manufacturer_ua', $product->manufacturer_ua) }}">
        </div>
    </div>

</div>
