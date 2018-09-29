<table id="categories-list-table" class="table table-bordered">

    <thead>
    <tr>
        <td colspan="2"><strong>Список характеристик</strong></td>
    </tr>

    </thead>

    <tbody>

    @if($attributes->count())

        @foreach($attributes as $attribute)

            <tr>


                <td>{{ $attribute->name }}</td>

                <td class="category-control-cell text-center">

                    <a href="{{ route('admin.attributes.show', ['id' => $attribute->id]) }}" data-toggle="tooltip"
                       title="Просмотреть" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>

                    <a href="{{ route('admin.attributes.edit', ['id' => $attribute->id]) }}" data-toggle="tooltip"
                       title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                    <form class="category-form ml-2" action="{{ route('admin.attributes.destroy', ['id' => $attribute->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="id" value="{{ $attribute->id }}">
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </form>

                </td>

            </tr>

        @endforeach

    @else

        <tr>
            <td colspan="4">Нет ни одной характеристики</td>
        </tr>

    @endif

    </tbody>
</table>