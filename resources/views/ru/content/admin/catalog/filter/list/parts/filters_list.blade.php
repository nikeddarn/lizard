@if($filters->count())
    <table class="table">

        <tbody>


        @foreach($filters as $filter)

            <tr>


                <td>{{ $filter->name }}</td>

                <td class="text-right">

                    <a href="{{ route('admin.filters.show', ['id' => $filter->id]) }}" data-toggle="tooltip"
                       title="Просмотреть" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>

                    <a href="{{ route('admin.filters.edit', ['id' => $filter->id]) }}" data-toggle="tooltip"
                       title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                    <form class="d-inline-block filter-form ml-lg-2"
                          action="{{ route('admin.filters.destroy', ['id' => $filter->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="_method" value="delete"/>
                        <input type="hidden" name="id" value="{{ $filter->id }}">
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

    <p>Нет ни одного фильтра</p>

@endif

@if($filters->hasMorePages())
    <div class="col-lg-12 my-4 items-pagination">{{$filters->links()}}</div>
@endif