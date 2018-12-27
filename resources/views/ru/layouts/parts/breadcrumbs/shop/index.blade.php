<nav aria-label="breadcrumb">
    <ol class="breadcrumb shop-breadcrumb">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        @foreach($breadcrumbs as $name => $href)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $href }}">{{ $name }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
