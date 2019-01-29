<form id="attribute-value-form" method="post" action="{{ route('admin.attribute.values.update', ['id' => $attributeValue->id]) }}" role="form"
      enctype="multipart/form-data">

    @csrf

    @method('PUT')

    <div class="card p-5 mb-5">

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="value_ru">Значение (ru)</label>
            </div>
            <div class="col-sm-8">
                <input id="value_ru" name="value_ru" type="text" required class="w-100"
                       value="{{ old('value_ru', $attributeValue->value_ru) }}">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="value_uk">Значение (ua)</label>
            </div>
            <div class="col-sm-8">
                <input id="value_uk" name="value_uk" type="text" required class="w-100"
                       value="{{ old('value_uk', $attributeValue->value_uk) }}">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="value_uk">Url</label>
            </div>
            <div class="col-sm-8">
                <input id="value_uk" name="url" type="text" required class="w-100"
                       value="{{ old('url', $attributeValue->url) }}">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-2">
                <label for="image">Изображение</label>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col">
                        @include('content.admin.catalog.attribute_value.update.parts.image')
                    </div>
                    <div class="col text-right">
                        @include('content.admin.catalog.attribute_value.update.parts.input_file')
                        @include('content.admin.catalog.attribute_value.update.parts.delete_file')
                    </div>
                </div>
            </div>
        </div>

    </div>

    <button type="submit" class="btn btn-primary">Изменить значение атрибута</button>

</form>
