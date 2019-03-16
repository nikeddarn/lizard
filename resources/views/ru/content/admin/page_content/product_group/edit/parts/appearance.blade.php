<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="name_ru">Заголовок (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ru" name="name_ru" type="text" class="w-100" value="{{ old('name_ru', $group->name_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="name_uk">Заголовок (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_uk" name="name_uk" type="text" class="w-100" value="{{ old('name_uk', $group->name_uk) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="min_count">Минимум продуктов для показа</label>
        </div>
        <div class="col col-sm-6 col-md-5 col-xl-3">
            <input id="min_count" name="min_count" type="number" class="product-counts" value="{{ old('min_count', $group->min_count_to_show) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="max_count">Максимум продуктов для показа</label>
        </div>
        <div class="col col-sm-6 col-md-5 col-xl-3">
            <input id="max_count" name="max_count" type="number" class="product-counts" value="{{ old('max_count', $group->max_count_to_show) }}">
        </div>
    </div>

</div>
