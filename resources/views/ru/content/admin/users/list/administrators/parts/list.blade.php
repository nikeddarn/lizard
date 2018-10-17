<table class="table">

    <thead>
    <tr>
        <td class="d-none d-lg-table-cell text-center"></td>
        <td><strong>Имя</strong></td>
        <td class="text-center"><strong>Телефон</strong></td>
        <td class="text-center"><strong>Роли</strong></td>
        <td></td>
    </tr>
    </thead>

    <tbody>


    @foreach($users as $user)

        <tr>

            <td class="d-none d-lg-table-cell">
                @if($user->avatar)
                    <img src="/storage/{{ $user->avatar }}" class="img-responsive table-image">
                @endif
            </td>

            <td>{{ $user->name }}</td>

            <td class="text-center">{{ $user->phone }}</td>

            <td>{{ implode(', ', $user->roles->pluck('title_ru')->toArray()) }}</td>

            <td class="text-right">

                <a href="{{ route('admin.users.show', ['id' => $user->id]) }}" data-toggle="tooltip"
                   title="Просмотреть" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>

                @can('delete', $user)
                    <form class="admin-delete-form d-inline-block"
                          action="{{ route('admin.users.destroy', ['id' => $user->id]) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </form>
                @endcan

            </td>

        </tr>

    @endforeach

    </tbody>
</table>

@if($users->links())
    <div class="col-lg-12 my-4 items-pagination">{{$users->links()}}</div>
@endif