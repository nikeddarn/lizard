<div class="container-fluid">
    <div class="row grid">
        @foreach($subcategories as $subcategory)
            <div class="grid-sizer grid-item col-4 mb-2">
                @if($subcategory->isLeaf())
                    <h4><a class="text-gray px-2 py-1"
                           href="{{ route('shop.category.leaf.index', ['url' => $subcategory->url]) }}">{{ $subcategory->name }}</a></h4>
                @else
                    <h4 class="text-gray px-2 py-1">{{ $subcategory->name }}&nbsp;
                        <i class="fa fa-caret-right"></i>
                    </h4>
                    @foreach($subcategory->children as $child)
                        <div>
                            <a class="text-gray px-2 py-1"
                               href="{{ $child->isLeaf() ? route('shop.category.leaf.index', ['url' => $child->url]) : route('shop.category.index', ['url' => $child->url]) }}">{{ $child->name }}</a>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
</div>