<table class="table">

    <thead>
    <tr class="text-center">
        <td><strong>Создан</strong></td>
        <td class="d-none d-xl-table-cell text-center"><strong>Фото</strong></td>
        <td><strong>Имя</strong></td>
        <td class="text-center"><strong>Телефон</strong></td>
        <td class="text-center"><strong>Роли</strong></td>
        <td></td>
    </tr>
    </thead>

    <tbody>


    @foreach($users as $user)

        <tr class="text-center">

            <td>{{ $user->created_at->format('d - m - Y') }}</td>

            <td class="d-none d-xl-table-cell">
                @if($user->avatar)
                    <img src="{{ url('/storage/' . $user->avatar) }}" class="img-responsive table-image-smaller">
                @endif
            </td>

            <td>{{ $user->name }}</td>

            <td class="text-center">{{ $user->phone }}</td>

            <td>
                @foreach($user->roles as $role)
                    <i class="mr-1">{{ $role->title_ru }}<br></i>
                @endforeach
            </td>

            <td class="d-flex justify-content-center align-items-start">

                <a href="{{ route('admin.users.administrators.show', ['id' => $user->id]) }}" data-toggle="tooltip"
                   title="Просмотреть" class="btn btn-primary">
                    <i class="svg-icon-larger" data-feather="eye"></i>
                </a>

                @can('modify', $user)
                    <form class="admin-delete-form d-inline-block ml-1"
                          action="{{ route('admin.users.administrators.destroy', ['id' => $user->id]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="svg-icon-larger" data-feather="trash-2"></i>
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
