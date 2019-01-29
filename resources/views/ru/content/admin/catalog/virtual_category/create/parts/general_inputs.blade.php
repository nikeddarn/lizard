<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="categories_id">Реальная категория</label>
        </div>
        <div class="col-sm-8">
            <select id="categories_id" name="categories_id" class="selectpicker" data-width="100%">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"{{ old('categories_id') == $category->id ? ' selected="selected"' : ''}}>
                        {{ $category->name_ru }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="attribute_values_id">Значение фильтра</label>
        </div>
        <div class="col-sm-8">
            <select id="attribute_values_id" name="attribute_values_id" class="selectpicker" data-width="100%">
                @foreach($attributeValues as $attributeValue)
                    <option value="{{ $attributeValue->id }}"{{ old('attribute_values_id') == $attributeValue->id ? ' selected="selected"' : ''}}>
                        {{ $attributeValue->value_ru }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

</div>

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
