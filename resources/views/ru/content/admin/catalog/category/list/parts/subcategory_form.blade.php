<table class="table table-bordered">

    <tbody>
    @foreach($subcategories as $subcategory)

        <tr>

            <td>{{ $subcategory->name }}</td>

            <td class="category-control-cell text-center">

                <a href="{{ route('admin.categories.show', ['id' => $category->id]) }}" data-toggle="tooltip"
                   title="Просмотреть" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>

                <a href="{{ route('admin.categories.edit', ['id' => $subcategory->id]) }}" data-toggle="tooltip"
                   title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                <form class="category-form ml-2" action="{{ route('admin.categories.destroy', ['id' => $subcategory->id]) }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="delete" />
                    <input type="hidden" name="id" value="{{ $category->id }}">
                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </form>

            </td>

            @if($subcategory->children->count())
                <td class="category-open-cell text-center">
                    <button class="btn btn-default" data-toggle="collapse"
                            data-target="#category-{{ $subcategory->id }}">
                        <i class="fa fa-folder-open" aria-hidden="true"></i>
                    </button>
                </td>
            @else
                <td class="category-open-cell text-center"></td>
            @endif

        </tr>

        @if($subcategory->children->count())
            <tr>
                <td colspan="4" class="p-0">
                    <div id="category-{{ $subcategory->id }}" class="collapse cat-depth-{{$subcategory->depth + 1}}">
                        <div class="py-4">
                            @include('content.admin.catalog.category.list.parts.subcategory_form', ['subcategories' => $subcategory->children])
                        </div>
                    </div>
                </td>
            </tr>
        @endif

    @endforeach

    </tbody>
</table>