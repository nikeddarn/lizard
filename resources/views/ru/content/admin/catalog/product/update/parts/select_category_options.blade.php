@foreach($categories as $category)

    <option class="cat-depth-{{$category->depth}}" value="{{ $category->id }}" {{ old('categories_id', $product->categories_id) == $category->id ? 'selected="selected"' : ''}}>
        {{ $category->name }}
    </option>

    @if($category->children->count())
        @include('content.admin.catalog.product.update.parts.select_category_options', ['categories' => $category->children])
    @endif

@endforeach