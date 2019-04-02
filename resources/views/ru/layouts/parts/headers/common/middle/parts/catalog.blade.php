<div id="mega-menu-content" class="row h-100">

    <div class="col-lg-3 pt-4">
        <div id="mega-menu-categories" class="list-group list-group-flush">
            @foreach($productCategories as $key => $category)
                <a id="mega-menu-category-{{ $category->id }}"
                   href="{{ $category->href }}"
                   class="nav-link list-group-item main-menu-category{{ $loop->first ? ' show' : '' }}"
                   aria-controls="mega-menu-children-{{ $category->id }}" data-category-id="{{ $category->id }}">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <h1 class="h6 my-0 text-gray-hover">{{ $category->name }}</h1>
                        <span>
                            <i class="svg-icon" data-feather="chevron-right"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div id="mega-menu-subcategories" class="col-lg-9 py-2">
        @foreach($productCategories as $category)
            <div id="mega-menu-children-{{ $category->id }}"
                 class="main-menu-subcategory{{ $loop->first ? ' show' : '' }}">
                @if(!$category->isLeaf())
                    <div class="row grid">
                        @foreach($category->children as $subcategory)
                            <div class="col-4 grid-item">
                                <a class="nav-link h2"
                                   href="{{ $subcategory->href }}">{{ $subcategory->name }}
                                </a>
                                @if(!$subcategory->isLeaf())
                                    @foreach($subcategory->children as $subcategoryChild)
                                        <a class="nav-link py-1"
                                           href="{{ $subcategoryChild->href }}">
                                            <h3 class="h6 m-0 text-gray">{{ $subcategoryChild->name }}</h3>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

</div>
