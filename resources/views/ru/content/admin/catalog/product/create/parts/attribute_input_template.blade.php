<div id="product-attribute-input-template">

    <div class="card p-5 mb-5 product-attribute-item d-none">

        <button type="button" class="btn btn-danger product-attribute-item-delete mr-md-2 mt-md-2" data-toggle="tooltip"
                title="Удалить атрибут">
            <i class="fa fa-trash-o"></i>&nbsp;
        </button>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required">Атрибут</label>
            </div>
            <div class="col-sm-8">
                <select name="attribute_id[]" class="w-100 attribute-id-select">
                    <option value="0" selected>Выберете атрибут</option>
                    @foreach($attributes as $attribute)
                        <option value="{{ $attribute->id }}"
                                data-attribute-values="{{ $attribute->attribute_values }}">{{ $attribute->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row form-group d-none">
            <div class="col-sm-2">
                <label class="required">Значение</label>
            </div>
            <div class="col-sm-8">
                <select name="attribute_value_id[]" class="w-100 attribute-value-id-select">
                    <option value="0" selected>Выберете значение</option>
                </select>
            </div>
        </div>

    </div>

</div>