<div id="category-filters-list"></div>

@if($filters->count())

    <div class="text-right mb-5">
        <button id="category-filter-add-button" class="btn btn-primary" type="button">
            <i class="fa fa-plus"></i>&nbsp;
            <span>Добавить фильтр</span>
        </button>
    </div>

@else

    <div class="mb-5">
        Нет ни одного фильтра. Создайте категорию затем создайте фильтры и добавьте их к категории.
    </div>

@endif