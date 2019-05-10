<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="min-show-filters-per-page">Минимальное количество открытых фильтров на странице</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="min-show-filters-per-page" class="form-control default-bootstrap-select-input" type="number"
                       name="min_show_filters_per_page"
                       value="{{ $filtersData['min'] }}" min="1" max="100">
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="max-show-filters-per-page">Максимальное количество открытых фильтров на странице</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="max-show-filters-per-page" class="form-control default-bootstrap-select-input" type="number"
                       name="max_show_filters_per_page"
                       value="{{ $filtersData['max'] }}" min="1" max="100">
            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-4">

    <div class="form-group mb-4">
        <label for="max-items-to-open-filter">Максимальное количесиво элементов фильтра для показа открытым</label>
        <div class="row">
            <div class="col col-sm-6 col-md-5 col-xl-3">
                <input id="max-items-to-open-filter" class="form-control default-bootstrap-select-input" type="number"
                       name="max_items_to_open_filter"
                       value="{{ $filtersData['max_values_count'] }}" min="1" max="100">
            </div>
        </div>
    </div>

</div>
