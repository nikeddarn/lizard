<table class="table">

    <tbody>


    @foreach($attributes as $attribute)

        <tr>


            <td>{{ $attribute->name }}</td>

            <td class="text-right">

                <a href="{{ route('admin.attributes.show', ['id' => $attribute->id]) }}" data-toggle="tooltip"
                   title="Просмотреть" class="btn btn-primary">
                    <i class="svg-icon-larger" data-feather="eye"></i>
                </a>

                <a href="{{ route('admin.attributes.edit', ['id' => $attribute->id]) }}" data-toggle="tooltip"
                   title="Редактировать" class="btn btn-primary">
                    <i class="svg-icon-larger" data-feather="edit"></i>
                </a>

                <form class="d-inline-block attribute-form ml-lg-2"
                      action="{{ route('admin.attributes.destroy', ['id' => $attribute->id]) }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="delete"/>
                    <input type="hidden" name="id" value="{{ $attribute->id }}">
                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                        <i class="svg-icon-larger" data-feather="trash-2"></i>
                    </button>
                </form>

            </td>

        </tr>

    @endforeach

    </tbody>
</table>
