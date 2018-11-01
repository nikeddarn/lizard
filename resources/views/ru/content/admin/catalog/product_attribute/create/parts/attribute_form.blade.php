<form id="product-attribute-form" method="post" action="{{ route('admin.products.attribute.store') }}" role="form">

    @csrf

    <input type="hidden" name="products_id" value="{{ $product->id }}">

    <div class="card product-attribute-item p-5 mb-5">

        <div class="row form-group">
            <div class="col-sm-2">
                <label for="attribute-select" class="required">Атрибут</label>
            </div>
            <div class="col-sm-8">
                <select id="attribute-select" name="attributes_id" class="w-100 attribute-id-select">
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
                <label for="attribute-value-select" class="required">Значение</label>
            </div>
            <div class="col-sm-8">
                <select id="attribute-value-select" name="attribute_values_id" class="w-100 attribute-value-id-select">
                    <option value="0" selected>Выберете значение</option>
                </select>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Сохранить атрибут</button>

</form>