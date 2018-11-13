<div id="product-attributes-list">

    @if(old('attribute_id'))

        @foreach(old('attribute_id') as $attributeIndex => $attribute_id)

            <div class="card p-5 mb-5 product-attribute-item">

                <button type="button" class="btn btn-danger product-attribute-item-delete mr-md-2 mt-md-2"
                        data-toggle="tooltip"
                        title="Удалить атрибут">
                    <i class="fa fa-trash-o"></i>&nbsp;
                </button>

                <div class="row form-group">
                    <div class="col-sm-2">
                        <label class="required">Атрибут</label>
                    </div>
                    <div class="col-sm-8">
                        <select name="attribute_id[]" class="w-100 attribute-id-select">
                            @foreach($attributes as $attribute)
                                <option value="{{ $attribute->id }}"
                                        data-attribute-values="{{ $attribute->attribute_values }}" {{ $attribute->id == $attribute_id ? 'selected' : '' }}>{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <p>{{gettype($attributes->get($attribute_id)->attribute_values)}}</p>
                {{--<div class="row form-group">--}}
                    {{--<div class="col-sm-2">--}}
                        {{--<label class="required">Значение</label>--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-8">--}}
                        {{--<select name="attribute_value_id[]" class="w-100 attribute-value-id-select">--}}
                            {{--@foreach($attributes->get($attribute_id)->attribute_values as $attributeValue)--}}
                                {{--<option value="{{ $attributeValue->id }}" {{ $attributeValue->id == old('attribute_value_id.' . $attributeIndex) ? 'selected' : '' }}>{{ $attributeValue->value }}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                    {{--</div>--}}
                {{--</div>--}}

            </div>

        @endforeach

    @endif

</div>

@if($attributes->count())

    <div class="text-right mb-5">
        <button id="product-attribute-add-button" class="btn btn-primary" type="button">
            <i class="fa fa-plus"></i>&nbsp;
            <span>Добавить атрибут</span>
        </button>
    </div>

@else

    <div class="mb-5">
        Нет ни одного атрибута. Создайте продукт затем создайте атрибуты и добавьте их к продукту.
    </div>

@endif