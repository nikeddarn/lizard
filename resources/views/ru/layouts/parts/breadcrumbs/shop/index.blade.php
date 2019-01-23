<nav aria-label="breadcrumb">
    <ol class="breadcrumb shop-breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a itemtype="https://schema.org/Thing" itemprop="item" href="{{ route('main') }}">
                <span itemprop="name">{{ config('app.name') }}</span>
            </a>
            <meta itemprop="position" content="1" />
        </li>
        @foreach($breadcrumbs as $name => $href)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
            @else
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemtype="https://schema.org/Thing" itemprop="item" href="{{ $href }}">
                        <span itemprop="name">{{ $name }}</span>
                    </a>
                    <meta itemprop="position" content="{{ $loop->index + 2 }}" />
                </li>
            @endif
        @endforeach
    </ol>
</nav>
