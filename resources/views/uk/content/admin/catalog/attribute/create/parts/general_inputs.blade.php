<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-5 col-xl-2">
            <label class="required" for="name_ru">Название (ru)</label>
        </div>
        <div class="col-7 col-xl-8">
            <input id="name_ru" name="name_ru" type="text" required class="w-100" value="{{ old('name_ru') }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-5 col-xl-2">
            <label class="required" for="name_uk">Название (ua)</label>
        </div>
        <div class="col-7 col-xl-8">
            <input id="name_uk" name="name_uk" type="text" required class="w-100" value="{{ old('name_uk') }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group align-items-start align-items-lg-center">
        <div class="col-5 col-xl-2">
            <label class="required" for="multiply_product_values">Мульти значение</label>
        </div>
        <div class="col-7 col-xl-10">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="multiply_product_values"
                       name="multiply_product_values" {{ (old('multiply_product_values') ? 'checked' : '') }}>
                <label class="custom-control-label" for="multiply_product_values">Разрешить присваивать несколько значений атрибута одному продукту</label>
            </div>
        </div>
    </div>

    <div class="row form-group align-items-start align-items-lg-center">
        <div class="col-5 col-xl-2">
            <label class="required" for="showable">Основной фильтр</label>
        </div>
        <div class="col-7 col-xl-10">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="showable"
                       name="showable" {{ (old('showable', true) ? 'checked' : '') }}>
                <label class="custom-control-label" for="indexable">Показывать фильтр с значениями данного атрибута как основной</label>
            </div>
        </div>
    </div>

    <div class="row form-group align-items-start align-items-lg-center">
        <div class="col-5 col-xl-2">
            <label class="required" for="indexable">Индексация</label>
        </div>
        <div class="col-7 col-xl-10">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="indexable"
                       name="indexable" {{ (old('indexable', true) ? 'checked' : '') }}>
                <label class="custom-control-label" for="indexable">Разрешить роботу индексировать категорию при применении фильтра с значением этого атрибута</label>
            </div>
        </div>
    </div>

</div>
