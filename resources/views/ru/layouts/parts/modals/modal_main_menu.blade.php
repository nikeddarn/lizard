<div class="modal fade modal-menu" id="mainMenuModal" tabindex="-1" role="dialog" aria-labelledby="mainMenuModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="menuModalLabel">
                    <a href="/" class="d-inline-block"><img src="/images/common/logo.png" height="35"></a>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="list-group list-group-no-border" id="list-menu" data-children=".list-submenu">

                    <a href="/" class="list-group-item list-group-item-action">
                        <i class="fa fa-home" aria-hidden="true"></i>&emsp;ГЛАВНАЯ</a>

                    <div class="list-group-collapse list-submenu">
                        <a class="list-group-item list-group-item-action" href="#categories-list" data-toggle="collapse"
                           aria-expanded="false" aria-controls="list-submenu-1">
                            <i class="fa fa-list-alt" aria-hidden="true"></i>&emsp;КАТАЛОГ ТОВАРОВ</a>
                        <div class="collapse" id="categories-list" data-parent="#list-menu">
                            <div class="list-group">
                                @foreach($megaMenuCategories as $category)
                                    <a class="list-group-item list-group-item-action"
                                       href="{{ route('shop.category.index', ['url' => $category->url]) }}">{{ $category->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>&emsp;КОРЗИНА</a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>&emsp;ФАВОРИТНЫЕ</a>

                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>&emsp;НЕДАВНИЕ</a>

                </div>

            </div>

        </div>
    </div>
</div>
