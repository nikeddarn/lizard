@foreach($categories as $category)

    <li class="card my-1">

        <div class="row">

            <div class="col-auto">
                <button class="btn btn-primary show-subcategory" data-toggle="collapse"
                        data-target="#category-{{ $category->id }}">
                    <i class="fa fa-plus"></i>
                </button>
            </div>

            <div class="col">
                <span>{{ $category->name }}</span>
            </div>

        </div>

    </li>

    <li class="my-1">
        <div id="category-{{ $category->id }}" class="collapse">
            <ul class="category-list">
                @include('content.admin.catalog.category.list.parts.attribute_list')
            </ul>
        </div>
    </li>

@endforeach
