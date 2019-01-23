<div class="container-fluid">
    <div class="row h-100 no-gutters">

        <div class="col-lg-3">
            <div id="mega-menu-categories" class="list-group list-group-flush">
                @foreach($productCategories as $key => $category)
                    <a id="mega-menu-category-{{ $category->id }}"
                       href="{{ $category->href }}"
                       class="nav-link list-group-item list-group-item-action{{ $loop->first ? ' show' : '' }}"
                       aria-controls="mega-menu-children-{{ $category->id }}">
                        <h1 class="h6 my-0 py-0">{{ $category->name }}</h1>
                    </a>
                @endforeach
            </div>
        </div>

        <div id="mega-menu-subcategories" class="col-lg-9">
            @foreach($productCategories as $category)
                <div id="mega-menu-children-{{ $category->id }}"
                     class="mega-menu-subcategory{{ $loop->first ? ' show' : '' }}">
                    @if(!$category->isLeaf())
                        <div class="row grid">
                            @foreach($category->children as $subcategory)
                                <div class="col-4 grid-item">
                                    <a class="nav-link"
                                       href="{{ $subcategory->href }}">
                                        <h2 class="h5 m-0 text-primary">{{ $subcategory->name }}</h2>
                                    </a>
                                    @if(!$subcategory->isLeaf())
                                        @foreach($subcategory->children as $subcategoryChild)
                                            <a class="nav-link py-1"
                                               href="{{ $subcategoryChild->href }}">
                                                <h3 class="h6 m-0">{{ $subcategoryChild->name }}</h3>
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
</div>
