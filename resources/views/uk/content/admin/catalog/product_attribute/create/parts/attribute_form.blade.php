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

                    @if(old('attributes_id'))
                        @foreach($attributes as $attribute)
                            <option value="{{ $attribute->id }}"
                                    {{ $attribute->id == old('attributes_id') ? 'selected' : '' }} data-attribute-values="{{ json_encode($attribute->attributeValues) }}">{{ $attribute->name_ru }}</option>
                        @endforeach
                    @else
                        <option value="0" selected disabled>Выберете атрибут</option>
                        @foreach($attributes as $attribute)
                            <option value="{{ $attribute->id }}"
                                    data-attribute-values="{{ json_encode($attribute->attributeValues) }}">{{ $attribute->name_ru }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>

        @if(old('attribute_values_id'))
            @include('content.admin.catalog.product_attribute.create.parts.old_attribute_value')
        @else
            @include('content.admin.catalog.product_attribute.create.parts.attribute_value')
        @endif

    </div>

    <button type="submit" class="btn btn-primary">Сохранить атрибут</button>

</form>