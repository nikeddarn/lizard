<form id="main-search-form" class="form-inline w-100" method="post" action="{{ route('shop.search.index', ['locale' => app()->getLocale() === config('app.canonical_locale') ? '' : app()->getLocale()]) }}" data-toggle="popover" data-content="Мінімум 2 символи">
    @csrf
    <div class="input-group input-group-search">
        <div class="input-group-prepend">
            <button id="header-hide-search-toggle" class="btn btn-light d-flex d-sm-none" type="button">
                <i class="svg-icon" data-feather="chevron-left"></i>
            </button>
        </div>
        <input type="text" name="search_for" class="form-control border-0 bg-light" placeholder="Знайти...">
        <div class="input-group-append">
            <button class="btn btn-light" type="submit">
                <i class="svg-icon" data-feather="search"></i>
            </button>
        </div>
    </div>
</form>
