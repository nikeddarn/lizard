<div class="card p-5 mb-5">

    <div class="row">

        <div class="col-sm-4">
            <label class="required" for="categories_id">Брать продукты из категории</label>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <select id="categories_id" name="categories_id" class="selectpicker w-100">
                    @include('content.admin.page_content.product_group.edit.parts.select_category_options')
                </select>
            </div>
        </div>

    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row">

        <div class="col-sm-4">
            <label class="required" for="categories_id">Метод выборки продуктов</label>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <select id="categories_id" name="cast_product_method" class="selectpicker w-100">
                    @foreach($castProductMethods as $method)
                        <option value="{{ $method->interface_id }}"{{ $method->id == $group->cast_product_methods_id ? ' selected' : ''}}>{{ $method->name_ru }}</option>
                        @endforeach
                </select>
            </div>
        </div>

    </div>

</div>
