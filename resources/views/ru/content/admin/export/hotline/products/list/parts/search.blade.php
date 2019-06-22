<form id="main-search-form" class="form-inline w-100" method="post" action="{{ route('admin.export.hotline.products.search', ['category_id' => $category->id]) }}" data-toggle="popover" data-content="Минимум 2 символа">
    @csrf
    <div class="input-group input-group-search w-50">
        <div class="input-group-prepend">
            <button id="header-hide-search-toggle" class="btn btn-light d-flex d-sm-none" type="button">
                <i class="svg-icon" data-feather="chevron-left"></i>
            </button>
        </div>
        <input type="text" name="search_for" class="form-control border-0 bg-light" placeholder="Найти...">
        <div class="input-group-append">
            <button class="btn btn-light" type="submit">
                <i class="svg-icon" data-feather="search"></i>
            </button>
        </div>
    </div>
</form>
