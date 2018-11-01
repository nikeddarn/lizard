@foreach($categories as $category)

    <option class="cat-depth-{{$category->depth}}" value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected="selected"' : ''}}>
        {{ $category->name }}
    </option>

    @if($category->children->count())
        @include('content.admin.catalog.product.create.parts.select_category_options', ['categories' => $category->children])
    @endif

@endforeach