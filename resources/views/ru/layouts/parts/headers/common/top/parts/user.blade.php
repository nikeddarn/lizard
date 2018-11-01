<div class="dropdown">

    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-user"></i>
        <span>{{ $userName }}</span>&nbsp;
        <span class="caret"></span>
    </button>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

        <a class="dropdown-item" href="{{ route('user.balance.show') }}">
            <span>Баланс</span>
        </a>

        <a class="dropdown-item" href="{{ route('user.notifications.current') }}">
            <span>Сообщения</span>&nbsp;
            @if(isset($userBadges['notifications']))
                <span class="badge badge-info">{{ $userBadges['notifications'] }}</span>
            @endif
        </a>

        <a class="dropdown-item" href="{{ route('user.shipments.current') }}">
            <span>Отгрузки</span>&nbsp;
            @if(isset($userBadges['shipments']))
                <span class="badge badge-info">{{ $userBadges['shipments'] }}</span>
            @endif
        </a>

        <a class="dropdown-item" href="{{ route('user.orders.current') }}">
            <span>Заказы</span>&nbsp;
            @if(isset($userBadges['orders']))
                <span class="badge badge-info">{{ $userBadges['orders'] }}</span>
            @endif
        </a>

        <a class="dropdown-item" href="{{ route('user.reclamations.current') }}">
            <span>Гарантия</span>&nbsp;
            @if(isset($userBadges['reclamations']))
                <span class="badge badge-info">{{ $userBadges['reclamations'] }}</span>
            @endif
        </a>

        <a class="dropdown-item" href="{{ route('user.payments.current') }}">
            <span>Платежи</span>&nbsp;
            @if(isset($userBadges['payments']))
                <span class="badge badge-info">{{ $userBadges['payments'] }}</span>
            @endif
        </a>

        <hr>

        <a class="dropdown-item" href="{{ route('user.favourites.index') }}">
            <span>Фаворитные</span>&nbsp;
            @if(isset($userBadges['payments']))
                <span class="badge badge-info">{{ $userBadges['favourites'] }}</span>
            @endif
        </a>

        <a class="dropdown-item" href="{{ route('user.recent.index') }}">
            <span>Недавние</span>&nbsp;
            @if(isset($userBadges['payments']))
                <span class="badge badge-info">{{ $userBadges['recent'] }}</span>
            @endif
        </a>

        <hr>

        <a class="dropdown-item" href="{{ route('user.profile.show') }}">Профиль</a>

        <a class="dropdown-item" href="{{ route('user.password.show') }}">Сменить пароль</a>

        <hr>

        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выйти</a>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>