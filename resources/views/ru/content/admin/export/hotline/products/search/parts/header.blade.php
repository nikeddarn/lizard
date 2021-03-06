<div class="card card-body mb-4">

    <div class="d-flex justify-content-between">

        <h1 class="h4 text-gray-hover">Подключение продуктов в категории {{ $category->name }} по запросу "{{ $searchText }}"</h1>

        <a href="{{ route('admin.export.hotline.products.list', ['category_id' => $category->id]) }}"
           data-toggle="tooltip"
           title="К списку продуктов без фильтра" class="btn btn-primary">
            <i class="svg-icon-larger" data-feather="list"></i>
        </a>

    </div>

</div>
