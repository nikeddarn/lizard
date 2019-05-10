<div class="list-group list-group-flush">

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.orders.index' ? ' active' : '' }}" href="{{ route('user.orders.index', ['locale' => request()->route('locale')]) }}">
            <i class="svg-icon mr-3" data-feather="shopping-bag"></i>
            <span>Заказы</span>
            @if(!empty($userBadges['orders']))
                <span class="badge rounded badge-light badge-menu">{{ $userBadges['orders'] }}</span>
            @endif
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.favourites.index' ? ' active' : '' }}" href="{{ route('user.favourites.index', ['locale' => request()->route('locale')]) }}">
            <i class="svg-icon mr-3" data-feather="heart"></i>
            <span>Фаворитные</span>
            @if(!empty($userBadges['favourites']))
                <span class="badge rounded badge-light badge-menu">{{ $userBadges['favourites'] }}</span>
            @endif
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.recent.index' ? ' active' : '' }}" href="{{ route('user.recent.index', ['locale' => request()->route('locale')]) }}">
            <i class="svg-icon mr-3" data-feather="clock"></i>
            <span>Недавние</span>
            @if(!empty($userBadges['recent']))
                <span class="badge rounded badge-light badge-menu">{{ $userBadges['recent'] }}</span>
            @endif
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.profile.edit' ? ' active' : '' }}" href="{{ route('user.profile.edit', ['locale' => request()->route('locale')]) }}">
            <i class="svg-icon mr-3" data-feather="user"></i>
            <span>Профиль</span>
        </a>

        <a class="list-group-item list-group-item-action{{ request()->route()->getName() === 'user.password.show' ? ' active' : '' }}" href="{{ route('user.password.show', ['locale' => request()->route('locale')]) }}">
            <i class="svg-icon mr-3" data-feather="key"></i>
            <span>Сменить пароль</span>
        </a>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <input type="hidden" name="locale" value="ru">

        <button class="list-group-item list-group-item-action cursor-pointer text-danger">
            <i class="svg-icon mr-3" data-feather="log-out"></i>
            <span>Выйти</span>
        </button>
    </form>

</div>
