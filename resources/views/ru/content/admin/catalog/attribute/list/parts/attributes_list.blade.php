@if($attributes->count())
    <table id="categories-list-table" class="table">

        <tbody>


        @foreach($attributes as $attribute)

            <tr>


                <td>{{ $attribute->name }}</td>

                <td class="text-right">

                    <a href="{{ route('admin.attributes.show', ['id' => $attribute->id]) }}" data-toggle="tooltip"
                       title="Просмотреть" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>

                    <a href="{{ route('admin.attributes.edit', ['id' => $attribute->id]) }}" data-toggle="tooltip"
                       title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                    <form class="d-inline-block attribute-form ml-lg-2"
                          action="{{ route('admin.attributes.destroy', ['id' => $attribute->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="_method" value="delete"/>
                        <input type="hidden" name="id" value="{{ $attribute->id }}">
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </form>

                </td>

            </tr>

        @endforeach

        </tbody>
    </table>

@else

    <p>Нет ни одного атрибута</p>

@endif

@if($attributes->hasMorePages())
    <div class="col-lg-12 my-4 items-pagination">{{$attributes->links()}}</div>
@endif