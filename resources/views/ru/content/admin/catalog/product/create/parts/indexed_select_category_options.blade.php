@foreach($categories as $category)

    <option class="cat-depth-{{$category->depth}}" value="{{ $category->id }}" {{ old('categories_id.' . $categoryIndex) == $category->id ? 'selected="selected"' : ''}}>
        {{ $category->name }}
    </option>

    @if($category->children->count())
        @include('content.admin.catalog.product.create.parts.indexed_select_category_options', ['categories' => $category->children, 'categoryIndex' => $categoryIndex])
    @endif

@endforeach