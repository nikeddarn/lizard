<div class="card card-pagination">
    <div class="card-body">

        @if($paginator->currentPage() === 1)
            <span class="btn btn-link btn-icon text-lizard" title="Предыдущая">
                <i class="svg-icon-larger" data-feather="chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-link btn-icon text-lizard" title="Предыдущая">
                <i class="svg-icon-larger" data-feather="chevron-left"></i>
            </a>
        @endif

        <div class="d-inline-flex">

            @if($paginator->lastPage() > 10)

                @if($paginator->currentPage() < 6)
                    @include('layouts.parts.pagination.products.parts.links', ['fromPage' => 1, 'toPage' => 7])
                    <span class="btn btn-icon rounded-pill bg-white">...</span>
                    @include('layouts.parts.pagination.products.parts.links', ['fromPage' => $paginator->lastPage() - 1, 'toPage' => $paginator->lastPage()])
                @elseif($paginator->currentPage() > $paginator->lastPage() - 6)
                    @include('layouts.parts.pagination.products.parts.links', ['fromPage' => 1, 'toPage' => 2])
                    <span class="btn btn-icon rounded-pill bg-white">...</span>
                    @include('layouts.parts.pagination.products.parts.links', ['fromPage' => $paginator->lastPage() - 6, 'toPage' => $paginator->lastPage()])
                @else
                    @include('layouts.parts.pagination.products.parts.links', ['fromPage' => 1, 'toPage' => 1])
                    <span class="btn btn-icon rounded-pill bg-white">...</span>
                    @include('layouts.parts.pagination.products.parts.links', ['fromPage' => $paginator->currentPage() -2, 'toPage' => $paginator->currentPage() + 2])
                    <span class="btn btn-icon rounded-pill bg-white">...</span>
                    @include('layouts.parts.pagination.products.parts.links', ['fromPage' => $paginator->lastPage(), 'toPage' => $paginator->lastPage()])
                @endif

            @else

                @include('layouts.parts.pagination.products.parts.links', ['fromPage' => 1, 'toPage' => $paginator->lastPage()])

            @endif

        </div>

        @if($paginator->currentPage() === $paginator->lastPage())
            <span class="btn btn-link btn-icon text-lizard" title="Следующая">
                    <i class="svg-icon" data-feather="chevron-right"></i>
                </span>
        @else
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-link btn-icon text-lizard" title="Следующая">
                <i class="svg-icon" data-feather="chevron-right"></i>
            </a>
        @endif


    </div>
</div>
