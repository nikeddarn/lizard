<form id="category-filter-form" method="post" action="{{ route('admin.categories.filter.store') }}" role="form">

    @csrf

    <input type="hidden" name="categories_id" value="{{ $category->id }}">

    <div class="card product-attribute-item p-5 mb-5">

        <div class="row form-group">
            <div class="col-sm-2">
                <label for="filter-select" class="required">Фильтр</label>
            </div>
            <div class="col-sm-8">
                <select id="filter-select" name="filters_id" class="w-100 selectpicker">
                    <option value="0" selected>Выберете фильтр</option>
                    @foreach($filters as $filter)
                        <option value="{{ $filter->id }}">{{ $filter->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <button type="submit" class="btn btn-primary">Сохранить фильтр</button>

</form>