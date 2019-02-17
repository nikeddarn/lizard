<div class="list-group list-group-flush">

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.balance.show' ? ' active' : '' }}" href="{{ route('user.balance.show', ['locale' => request()->route('locale')]) }}">Баланс</a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.notifications.current' ? ' active' : '' }}" href="{{ route('user.notifications.current', ['locale' => request()->route('locale')]) }}">Сообщения
            @if(!empty($userBadges['notifications']))
                <span class="badge rounded badge-light badge-menu">{{ $userBadges['notifications'] }}</span>
            @endif
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.shipments.current' ? ' active' : '' }}" href="{{ route('user.shipments.current', ['locale' => request()->route('locale')]) }}">Отгрузки
            @if(!empty($userBadges['shipments']))
                <span class="badge rounded badge-light badge-menu">{{ $userBadges['shipments'] }}</span>
            @endif
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.orders.current' ? ' active' : '' }}" href="{{ route('user.orders.current', ['locale' => request()->route('locale')]) }}">Заказы
            @if(!empty($userBadges['orders']))
                <span class="badge rounded badge-light badge-menu">{{ $userBadges['orders'] }}</span>
            @endif
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.reclamations.current' ? ' active' : '' }}" href="{{ route('user.reclamations.current', ['locale' => request()->route('locale')]) }}">Гарантия
            @if(!empty($userBadges['reclamations']))
                <span class="badge rounded badge-light badge-menu">{{ $userBadges['reclamations'] }}</span>
            @endif
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.payments.current' ? ' active' : '' }}" href="{{ route('user.payments.current', ['locale' => request()->route('locale')]) }}">Платежи
            @if(!empty($userBadges['payments']))
                <span class="badge rounded badge-light badge-menu">{{ $userBadges['payments'] }}</span>
            @endif
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.favourites.index' ? ' active' : '' }}" href="{{ route('user.favourites.index', ['locale' => request()->route('locale')]) }}">Фаворитные
            @if(!empty($userBadges['favourites']))
                <span class="badge rounded badge-light badge-menu">{{ $userBadges['favourites'] }}</span>
            @endif
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.recent.index' ? ' active' : '' }}" href="{{ route('user.recent.index', ['locale' => request()->route('locale')]) }}">Недавние
            @if(!empty($userBadges['recent']))
                <span class="badge rounded badge-light badge-menu">{{ $userBadges['recent'] }}</span>
            @endif
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.profile.show' ? ' active' : '' }}" href="{{ route('user.profile.show', ['locale' => request()->route('locale')]) }}">Профиль</a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.password.show' ? ' active' : '' }}" href="{{ route('user.password.show', ['locale' => request()->route('locale')]) }}">Сменить пароль</a>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="list-group-item list-group-item-action cursor-pointer">Выйти</button>
    </form>

</div>
