<ul class="nav">
    <li class="dropdown dropdown-hover">

        <a href="#" class="nav-link dropdown-toggle caret-off text-gray py-1 pr-0" id="top-panel-user-menu"
           data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false" onclick="return false;">
            <i class="svg-icon" data-feather="user"></i>
            <span>{{ $user->name }}</span>&nbsp;
            <i class="svg-icon" data-feather="chevron-down"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-right m-0" aria-labelledby="top-panel-user-menu">

            <a class="dropdown-item d-flex align-items-center px-3" href="{{ route('user.orders.index', ['locale' => request()->route('locale')]) }}">
                <span>Замовлення</span>&nbsp;
                @if(!empty($userBadges['orders']))
                    <span class="badge rounded badge-primary badge-menu">{{ $userBadges['orders'] }}</span>
                @endif
            </a>

            <hr>

            <a class="dropdown-item d-flex align-items-center px-3" href="{{ route('user.favourites.index', ['locale' => request()->route('locale')]) }}">Фаворитні
                @if(!empty($userBadges['favourites']))
                    <span class="badge rounded badge-primary badge-menu">{{ $userBadges['favourites'] }}</span>
                @endif
            </a>

            <a class="dropdown-item d-flex align-items-center px-3" href="{{ route('user.recent.index', ['locale' => request()->route('locale')]) }}">
                <span>Нещодавні</span>&nbsp;
                @if(!empty($userBadges['recent']))
                    <span class="badge rounded badge-primary badge-menu">{{ $userBadges['recent'] }}</span>
                @endif
            </a>

            <hr>

            <a class="dropdown-item d-flex align-items-center px-3" href="{{ route('user.profile.edit', ['locale' => request()->route('locale')]) }}">Профіль</a>

            <a class="dropdown-item d-flex align-items-center px-3" href="{{ route('user.password.show', ['locale' => request()->route('locale')]) }}">Змінити пароль</a>

            <hr>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <input type="hidden" name="locale" value="uk">

                <button class="dropdown-item cursor-pointer d-flex align-items-center px-3">Вийти</button>
            </form>
        </div>
    </li>
</ul>
