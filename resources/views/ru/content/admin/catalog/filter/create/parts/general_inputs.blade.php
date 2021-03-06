<form id="filter-form" method="post" action="{{ route('admin.filters.store') }}" role="form">

    @csrf

    <div class="card p-5 mb-5">

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="name_ru">Название (ru)</label>
            </div>
            <div class="col-sm-8">
                <input id="name_ru" name="name_ru" type="text" required class="w-100" value="{{ old('name_ru') }}">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-2">
                <label class="required" for="name_uk">Название (ua)</label>
            </div>
            <div class="col-sm-8">
                <input id="name_uk" name="name_uk" type="text" required class="w-100" value="{{ old('name_uk') }}">
            </div>
        </div>

    </div>

    <button type="submit" class="btn btn-primary">Создать фильтр</button>

</form>