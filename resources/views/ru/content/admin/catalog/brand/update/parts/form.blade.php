<form id="brand-form" method="post" action="{{ route('admin.brands.update', ['id' => $brand->id]) }}" role="form"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card p-5 mb-5">
        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="name">Название</label>
            </div>
            <div class="col-sm-8">
                <input id="name" name="name" type="text" required class="w-100" value="{{ old('name', $brand->name) }}">
            </div>
        </div>
    </div>

    <div class="card p-5 mb-5">
        <div class="row form-group">
            <div class="col-sm-2">
                <label>Изображение</label>
            </div>
            <div class="col-sm-8">
                @include('elements.input_image.index')
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Изменить бренд</button>

</form>