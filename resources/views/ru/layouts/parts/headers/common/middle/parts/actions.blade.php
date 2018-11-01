<nav class="text-center">

    <a id="header-cart-products" class="mx-md-2 d-inline-block header-action text-gray" href="#" title="Корзина">
        <i class="fa fa-shopping-bag fa-2x" aria-hidden="true"></i>
    </a>

    <a id="header-favourite-products" class="mx-md-2 d-inline-block header-action text-gray" href="{{ route('user.favourites.index') }}" title="Фаворитные товары">
        @if(isset($headerActionBadges['favourite']) && $headerActionBadges['favourite'])
            <span>{{ $headerActionBadges['favourite']}}</span>
        @endif
        <i class="fa fa-heart-o fa-2x" aria-hidden="true"></i>
    </a>

    <a id="header-recent-products" class="mx-md-2 d-inline-block header-action text-gray" href="{{ route('user.recent.index') }}" title="Недавно просмотренные товары">
        @if(isset($headerActionBadges['recent']) && $headerActionBadges['recent'])
            <span>{{ $headerActionBadges['recent']}}</span>
        @endif
        <i class="fa fa-clock-o fa-2x" aria-hidden="true"></i>
    </a>
</nav>