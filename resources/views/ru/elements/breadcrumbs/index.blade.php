<div class="breadcrumb-wrapper">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb py-2">
                <li class="breadcrumb-item"><a href="/">Главная</a></li>
                @foreach($breadcrumbs as $breadcrumb)
                    @if ($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb->name }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->name }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
</div>