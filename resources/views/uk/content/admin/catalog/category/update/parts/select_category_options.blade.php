@foreach($categories as $parentCategory)

    <option class="cat-depth-{{$parentCategory->depth}}" value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected="selected"' : ''}}>
        {{ $parentCategory->name }}
    </option>

    @if($parentCategory->children->count())
        @include('content.admin.catalog.category.update.parts.select_category_options', ['categories' => $parentCategory->children])
    @endif

@endforeach
