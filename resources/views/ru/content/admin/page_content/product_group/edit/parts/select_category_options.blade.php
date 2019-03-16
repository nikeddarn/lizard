@foreach($categories as $category)

    <option class="cat-depth-{{$category->depth}}" value="{{ $category->id }}" {{ old('categories_id', $group->categories_id) == $category->id ? 'selected="selected"' : ''}}>
        {{ $category->name }}
    </option>

    @if($category->children->count())
        @include('content.admin.page_content.product_group.edit.parts.select_category_options', ['categories' => $category->children])
    @endif

@endforeach
