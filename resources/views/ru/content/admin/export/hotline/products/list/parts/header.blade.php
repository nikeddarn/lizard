<div class="card card-body mb-4">

    <div class="d-flex justify-content-between">

        <h1 class="h4 text-gray-hover">Подключение продуктов в категории {{ $category->name }}</h1>

        <a href="{{ url()->previous() }}" data-toggle="tooltip" title="Отменить"
           class="btn btn-primary ml-1">
            <i class="svg-icon-larger" data-feather="corner-up-left"></i>
        </a>

    </div>

</div>
