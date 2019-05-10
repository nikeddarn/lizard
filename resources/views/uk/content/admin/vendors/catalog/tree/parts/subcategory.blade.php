<li class="my-1">
    <div id="category-{{ $category->id }}" class="collapse">
        <ul class="category-list">
            @include('content.admin.vendors.catalog.tree.parts.categories', ['categories' => $category->children,])
        </ul>
    </div>
</li>
