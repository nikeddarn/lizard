@for ($i = $fromPage; $i <= $toPage; $i++)
    @if($paginator->currentPage() === $i)
        <span class="btn btn-icon rounded-pill btn-primary">{{ $i }}</span>
    @else
        <a href="{{ $paginator->url($i) }}" class="btn btn-icon rounded-pill btn-light">{{ $i }}</a>
    @endif
@endfor
