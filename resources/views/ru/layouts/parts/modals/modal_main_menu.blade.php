<div class="modal left fade" id="mainMenuModal" tabindex="-1" role="dialog" aria-labelledby="mainMenuModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header d-block">
                <a href="{{ url('main') }}">
                    <img src="{{ url('/images/common/logo_small.png') }}" alt="logotype">
                </a>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="nav-main list-group list-group-no-border" id="list-menu" data-children=".list-submenu">

                    <a href="{{ url('home') }}" class="nav-link list-group-item list-group-item-action">
                        <i class="svg-icon-larger" data-feather="home"></i>
                        <span class="ml-3">Главная</span>
                    </a>

                    <div class="list-group-collapse list-submenu">
                        <a class="nav-link list-group-item list-group-item-action" href="#categories-list"
                           data-toggle="collapse"
                           aria-expanded="false" aria-controls="list-submenu-1">
                            <i class="svg-icon-larger" data-feather="shopping-bag"></i>
                            <span class="ml-3">Каталог товаров</span>
                        </a>
                        <div class="collapse" id="categories-list" data-parent="#list-menu">
                            <div class="list-group">
                                @foreach($productCategories as $category)
                                    <a class="list-group-item list-group-item-action"
                                       href="{{ route('shop.category.index', ['url' => $category->url]) }}">{{ $category->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
