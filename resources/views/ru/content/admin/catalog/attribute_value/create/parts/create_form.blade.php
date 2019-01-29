<form id="attribute-value-form" method="post" action="{{ route('admin.attributes.value.store') }}" role="form" enctype="multipart/form-data">

    @csrf

    <input type="hidden" name="attributeId" value="{{ $attribute->id }}">

    <div class="card p-5 mb-5">

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="value_ru">Значение (ru)</label>
            </div>
            <div class="col-sm-8">
                <input id="value_ru" name="value_ru" type="text" required class="w-100" value="{{ old('value_ru') }}">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="value_uk">Значение (ua)</label>
            </div>
            <div class="col-sm-8">
                <input id="value_uk" name="value_uk" type="text" required class="w-100" value="{{ old('value_uk') }}">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="url">Url</label>
            </div>
            <div class="col-sm-8">
                <input id="url" name="url" type="text" required class="w-100" value="{{ old('url') }}" placeholder="Last part of filtered products route">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-2">
                <label for="image">Изображение</label>
            </div>
            <div class="col-sm-8">
                @include('elements.input_image.index')
            </div>
        </div>

    </div>

    <button type="submit" class="btn btn-primary">Создать значение атрибута</button>

</form>
