<div class="list-group list-group-flush pr-4 ml-3">

    <div id="left-menu-categories">
        @foreach($productCategories as $key => $category)

            <a id="left-menu-category-{{ $category->id }}"
               href="{{ $category->href }}"
               class="nav-link list-group-item pr-1 main-menu-category left-menu-category"
               aria-controls="left-menu-children-{{ $category->id }}" data-toggle="dropdown" aria-haspopup="true"
               aria-expanded="false" data-category-id="{{ $category->id }}">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <h1 class="h6 my-0 py-0 text-gray-hover">{{ $category->name }}</h1>
                    <i class="svg-icon" data-feather="chevron-right"></i>
                </div>
            </a>

        @endforeach
    </div>

</div>


