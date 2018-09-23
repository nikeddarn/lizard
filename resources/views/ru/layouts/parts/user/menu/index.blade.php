<nav id="userNavbar" class="navbar bg-light">

    <ul class="navbar-nav">

        <li class="nav-item @if(strpos(Illuminate\Support\Facades\Route::currentRouteName(), 'balance')) active @endif">
            <a class="nav-link" href="{{ route('user.balance.show') }}">
                <span>Баланс</span>
            </a>
        </li>

        <li class="nav-item @if(strpos(Illuminate\Support\Facades\Route::currentRouteName(), 'notifications')) active @endif">
            <a class="nav-link" href="{{ route('user.notifications.current') }}">
                <span>Сообщения</span>&nbsp;
                @if(isset($userBadges['notifications']))
                    <span class="badge badge-info">{{ $userBadges['notifications'] }}</span>
                @endif
            </a>
        </li>


        <li class="nav-item @if(strpos(Illuminate\Support\Facades\Route::currentRouteName(), 'shipments')) active @endif">
            <a class="nav-link" href="{{ route('user.shipments.current') }}">
                <span>Отгрузки</span>&nbsp;
                @if(isset($userBadges['shipments']))
                    <span class="badge badge-info">{{ $userBadges['shipments'] }}</span>
                @endif
            </a>
        </li>

        <li class="nav-item @if(strpos(Illuminate\Support\Facades\Route::currentRouteName(), 'orders')) active @endif">
            <a class="nav-link" href="{{ route('user.orders.current') }}">
                <span>Заказы</span>&nbsp;
                @if(isset($userBadges['orders']))
                    <span class="badge badge-info">{{ $userBadges['orders'] }}</span>
                @endif
            </a>
        </li>

        <li class="nav-item @if(strpos(Illuminate\Support\Facades\Route::currentRouteName(), 'reclamations')) active @endif">
            <a class="nav-link" href="{{ route('user.reclamations.current') }}">
                <span>Гарантия</span>&nbsp;
                @if(isset($userBadges['reclamations']))
                    <span class="badge badge-info">{{ $userBadges['reclamations'] }}</span>
                @endif
            </a>
        </li>

        <li class="nav-item @if(strpos(Illuminate\Support\Facades\Route::currentRouteName(), 'payments')) active @endif">
            <a class="nav-link" href="{{ route('user.payments.current') }}">
                <span>Платежи</span>&nbsp;
                @if(isset($userBadges['payments']))
                    <span class="badge badge-info">{{ $userBadges['payments'] }}</span>
                @endif
            </a>
        </li>

        <li>
            <hr>
        </li>

        <li class="nav-item @if(strpos(Illuminate\Support\Facades\Route::currentRouteName(), 'profile')) active @endif">
            <a class="nav-link" href="{{ route('user.profile.show') }}">Профиль</a>
        </li>

        <li class="nav-item @if(strpos(Illuminate\Support\Facades\Route::currentRouteName(), 'password')) active @endif">
            <a class="nav-link" href="{{ route('user.password.show') }}">Сменить пароль</a>
        </li>

        <li>
            <hr>
        </li>

        <li>
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выйти</a>
        </li>

    </ul>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>