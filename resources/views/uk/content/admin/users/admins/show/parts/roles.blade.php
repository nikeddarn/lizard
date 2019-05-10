<div class="table-responsive">
    <table class="table">

        <thead>
        <tr class="text-center">
            <td><strong>Добавлена</strong></td>
            <td><strong>Роль</strong></td>
            <td><strong>Действия</strong></td>
        </tr>
        </thead>

        <tbody>

        @foreach($user->userRoles as $userRole)
            <tr class="text-center">

                <td>{{ $userRole->created_at->format('d - m - Y') }}</td>

                <td>
                    <i>{{ $userRole->role->title_ru }}</i>
                </td>

                <td class="d-flex justify-content-center align-items-start">
                    @can('modify', $user)
                        <form class="d-inline-block user-role-delete-form"
                              action="{{ route('admin.users.role.destroy') }}" method="post"
                              data-roles-count="{{ $user->userRoles->count() }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="users_id" value="{{ $user->id }}">
                            <input type="hidden" name="roles_id" value="{{ $userRole->role->id }}">
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                    title="Удалить роль сотрудника">
                                <i class="svg-icon-larger" data-feather="trash-2"></i>
                            </button>
                        </form>
                    @endcan
                </td>

            </tr>
        @endforeach

        </tbody>

    </table>
</div>

@can('modify', $user)
    <div class="text-right my-4">
        <a href="{{route('admin.users.role.create', ['id' => $user->id])}}" class="btn btn-primary">
            <i class="svg-icon-larger" data-feather="plus"></i>
            <span>Добавить роль сотруднику</span>
        </a>
    </div>
@endcan
