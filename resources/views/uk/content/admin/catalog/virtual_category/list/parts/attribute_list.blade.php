<div class="table-responsive my-4">
    <table class="table table-striped m-0">

        <thead>
        <tr class="text-center">
            <td><strong>Виртуальная категория</strong></td>
            <td><strong>Фильтр</strong></td>
            <td></td>
        </tr>
        </thead>

        <tbody>

        @foreach($category->virtualCategories as $virtualCategory)

            <tr class="text-center">

                <td>
                    <a href="{{ route('shop.category.filter.single', ['url' => $category->url, 'filter' => $virtualCategory->attributeValue->url]) }}">{{ $virtualCategory->name_ru }}</a>
                </td>

                <td>{{ $virtualCategory->attributeValue->value_ru }}</td>

                <td>

                    <div class="d-flex">

                        <a href="{{ route('admin.categories.virtual.show', ['id' => $virtualCategory->id]) }}" data-toggle="tooltip"
                           title="Просмотреть" class="btn btn-primary">
                            <i class="svg-icon-larger" data-feather="eye"></i>
                        </a>

                        <a href="{{ route('admin.categories.virtual.edit', ['id' => $virtualCategory->id]) }}" data-toggle="tooltip"
                           title="Редактировать" class="btn btn-primary mx-1">
                            <i class="svg-icon-larger" data-feather="edit"></i>
                        </a>

                        <form id="delete-virtual-category-form" class="d-inline-block"
                              action="{{ route('admin.categories.virtual.destroy', ['id' => $virtualCategory->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="delete"/>
                            <input type="hidden" name="id" value="{{ $virtualCategory->id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                                <i class="svg-icon-larger" data-feather="trash-2"></i>
                            </button>
                        </form>

                    </div>

                </td>

            </tr>

        @endforeach

        </tbody>
    </table>
</div>
