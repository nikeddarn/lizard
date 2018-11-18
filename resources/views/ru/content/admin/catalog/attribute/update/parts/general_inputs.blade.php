<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ru">Название (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ru" name="name_ru" type="text" required class="w-100" value="{{ old('name_ru', $attribute->name_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ua">Название (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ua" name="name_ua" type="text" required class="w-100" value="{{ old('name_ua', $attribute->name_ua) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group align-items-center">
        <div class="col-sm-2">
            <label class="required" for="multiply_product_values">Мульти значение</label>
        </div>
        <div class="col-sm-8">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="multiply_product_values"
                       name="multiply_product_values" {{ (old('multiply_product_values', $attribute->multiply_product_values) ? 'checked' : '') }}>
                <label class="custom-control-label" for="multiply_product_values">Разрешить присваивать несколько значений атрибута одному продукту</label>
            </div>
        </div>
    </div>

</div>