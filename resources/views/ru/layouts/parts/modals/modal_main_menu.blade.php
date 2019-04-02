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


                    <div class="list-group-collapse list-submenu mb-2">
                        <a class="btn btn-primary w-100" href="#categories-list"
                           data-toggle="collapse"
                           aria-expanded="false" aria-controls="list-submenu-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="ml-3">Каталог товаров</span>
                                <i class="svg-icon-larger" data-feather="chevron-down"></i>
                            </div>
                        </a>
                        <div class="collapse" id="categories-list" data-parent="#list-menu">
                            <div class="list-group">
                                @foreach($productCategories as $category)
                                    <a class="list-group-item list-group-item-action"
                                       href="{{ $category->href }}">{{ $category->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <a href="{{ url('home') }}" class="nav-link list-group-item list-group-item-action">
                        <i class="svg-icon-larger" data-feather="home"></i>
                        <span class="ml-3">Главная</span>
                    </a>

                    <a href="{{ url('shop.about.index') }}" class="nav-link list-group-item list-group-item-action">
                        <i class="svg-icon-larger" data-feather="info"></i>
                        <span class="ml-3">О нас</span>
                    </a>

                    <a href="{{ url('shop.contacts.index') }}" class="nav-link list-group-item list-group-item-action">
                        <i class="svg-icon-larger" data-feather="phone"></i>
                        <span class="ml-3">Контакты</span>
                    </a>

                    <a href="{{ url('shop.delivery.index') }}" class="nav-link list-group-item list-group-item-action">
                        <i class="svg-icon-larger" data-feather="truck"></i>
                        <span class="ml-3">Способы доставки</span>
                    </a>

                    <a href="{{ url('shop.payments.index') }}" class="nav-link list-group-item list-group-item-action">
                        <i class="svg-icon-larger" data-feather="dollar-sign"></i>
                        <span class="ml-3">Способы оплаты</span>
                    </a>

                    <a href="{{ url('shop.return.index') }}" class="nav-link list-group-item list-group-item-action">
                        <i class="svg-icon-larger" data-feather="rewind"></i>
                        <span class="ml-3">Возврат товара</span>
                    </a>

                    <a href="{{ url('shop.warranty.index') }}" class="nav-link list-group-item list-group-item-action">
                        <i class="svg-icon-larger" data-feather="settings"></i>
                        <span class="ml-3">Гарантия</span>
                    </a>

                </div>

            </div>

        </div>
    </div>
</div>
