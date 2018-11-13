<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ru">Название (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ru" name="name_ru" type="text" required class="w-100"
                   value="{{ old('name_ru', $product->name_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="name_ua">Название (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="name_ua" name="name_ua" type="text" required class="w-100"
                   value="{{ old('name_ua', $product->name_ua) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label class="required" for="url">URL</label>
        </div>
        <div class="col-sm-8">
            <input id="url" name="url" type="text" required class="w-100"
                   placeholder="site.name/products/your-entering-url" value="{{ old('url', $product->url) }}">
        </div>
    </div>

</div>

<div class="card p-5 mb-5">
    <div class="row form-group">
        <div class="col-sm-2">
            <label for="brands_id">Бренд</label>
        </div>
        <div class="col-sm-8">
            <select id="brands_id" name="brands_id" class="selectpicker w-100">

                @if($product->brand)
                    <option value="0">Нет бренда</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $brand->id == old('brands_id', $product->brand->id) ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                @else
                    <option selected value="0">Нет бренда</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                @endif

            </select>
        </div>
    </div>
</div>

<div class="card p-5 mb-5">

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="manufacturer_ru">Страна производитель (ru)</label>
        </div>
        <div class="col-sm-8">
            <input id="manufacturer_ru" name="manufacturer_ru" type="text" class="w-100"
                   value="{{ old('manufacturer_ru', $product->manufacturer_ru) }}">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            <label for="manufacturer_ua">Страна производитель (ua)</label>
        </div>
        <div class="col-sm-8">
            <input id="manufacturer_ua" name="manufacturer_ua" type="text" class="w-100"
                   value="{{ old('manufacturer_ua', $product->manufacturer_ua) }}">
        </div>
    </div>

</div>