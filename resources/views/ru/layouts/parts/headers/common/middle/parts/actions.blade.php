<div id="header-actions" class="d-inline-block">

    <ul class="nav">

        <li class="d-inline-block d-md-none nav-item ml-sm-2">
            <button id="header-show-search-toggle" class="btn btn-link nav-link nav-icon" title="Поиск">
                <i class="svg-icon-larger" data-feather="search"></i>
            </button>
        </li>

        <li class="nav-item dropdown dropdown-hover ml-sm-2">
            <a id="header-cart-products" class="nav-link nav-icon dropdown-toggle" href="#" title="Корзина">
                <i class="svg-icon-larger" data-feather="shopping-cart"></i>
            </a>
        </li>

        <li class="nav-item dropdown dropdown-hover ml-sm-2">
            <a id="header-favourite-products" class="nav-link nav-icon dropdown-toggle"
               href="{{ route('user.favourites.index', ['locale' => request()->route('locale')]) }}" title="Фаворитные товары">
                <i class="svg-icon-larger" data-feather="heart"></i>
                @if(isset($userBadges['favourites']) && $userBadges['favourites'] > 0)
                    <span class="badge badge-primary">{{ $userBadges['favourites']}}</span>
                @else
                    <span class="badge badge-primary"></span>
                @endif
            </a>
        </li>

        <li class="nav-item dropdown dropdown-hover ml-sm-2">
            <a id="header-recent-products" class="nav-link nav-icon dropdown-toggle"
               href="{{ route('user.recent.index', ['locale' => request()->route('locale')]) }}" title="Недавно просмотренные товары">
                <i class="svg-icon-larger" data-feather="clock"></i>
                @if(isset($userBadges['recent']) && $userBadges['recent'] > 0)
                    <span class="badge badge-primary">{{ $userBadges['recent'] }}</span>
                @else
                    <span class="badge badge-primary"></span>
                @endif
            </a>
        </li>

    </ul>

</div>
