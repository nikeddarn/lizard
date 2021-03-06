<ul class="nav">
    <li class="dropdown dropdown-hover">

        <a href="#" class="nav-link dropdown-toggle caret-off text-gray py-1 px-0" id="top-panel-user-menu"
           data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false" onclick="return false;">
            <i class="svg-icon" data-feather="user"></i>
            <span>{{ $user->name }}</span>&nbsp;
            <i class="svg-icon" data-feather="chevron-down"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-right m-0" aria-labelledby="top-panel-user-menu">

            <a class="dropdown-item d-flex align-items-center px-3" href="{{ route('user.orders.index', ['locale' => request()->route('locale')]) }}">
                <span>Заказы</span>&nbsp;
                @if(!empty($userBadges['orders']))
                    <span class="badge rounded badge-primary badge-menu">{{ $userBadges['orders'] }}</span>
                @endif
            </a>

            <hr>

            <a class="dropdown-item d-flex align-items-center px-3" href="{{ route('user.favourites.index', ['locale' => request()->route('locale')]) }}">Фаворитные
                @if(!empty($userBadges['favourites']))
                    <span class="badge rounded badge-primary badge-menu">{{ $userBadges['favourites'] }}</span>
                @endif
            </a>

            <a class="dropdown-item d-flex align-items-center px-3" href="{{ route('user.recent.index', ['locale' => request()->route('locale')]) }}">
                <span>Недавние</span>&nbsp;
                @if(!empty($userBadges['recent']))
                    <span class="badge rounded badge-primary badge-menu">{{ $userBadges['recent'] }}</span>
                @endif
            </a>

            <hr>

            <a class="dropdown-item d-flex align-items-center px-3" href="{{ route('user.profile.edit', ['locale' => request()->route('locale')]) }}">Профиль</a>

            <a class="dropdown-item d-flex align-items-center px-3" href="{{ route('user.password.show', ['locale' => request()->route('locale')]) }}">Сменить пароль</a>

            <hr>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <input type="hidden" name="locale" value="ru">

                <button class="dropdown-item cursor-pointer d-flex align-items-center px-3">Выйти</button>
            </form>
        </div>
    </li>
</ul>
