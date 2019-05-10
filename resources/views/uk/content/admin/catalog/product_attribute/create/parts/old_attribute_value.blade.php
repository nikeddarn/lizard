<div class="row form-group select-attribute-value">
    <div class="col-sm-2">
        <label for="attribute-value-select" class="required">Значение</label>
    </div>
    <div class="col-sm-8">
        <select id="attribute-value-select" name="attribute_values_id" class="attribute-value-id-select"
                data-width="100%">
            @foreach($attributes->get(old('attributes_id'))->attributeValues as $attributeValue)
                <option value="{{ $attributeValue->id }}" {{ $attributeValue->id == old('attribute_values_id') ? 'selected' : '' }}>{{ $attributeValue->name }}</option>
            @endforeach
        </select>
    </div>
</div>