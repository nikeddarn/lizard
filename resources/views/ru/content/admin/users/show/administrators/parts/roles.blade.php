<div class="card py-2 px-1 p-lg-5 mb-5">

    <h4 class="mb-5 text-center">Роли сотрудника</h4>

    <table class="table">

        <tbody>

        @foreach($user->userRoles as $userRole)

            <tr>

                <td>{{ $userRole->role->title_ru }}</td>


                @can('delete', $userRole)

                <td class="text-right">

                    <form class="d-inline-block user-role-delete-form"
                          action="{{ route('admin.users.role.destroy') }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="users_id" value="{{ $user->id }}">
                        <input type="hidden" name="roles_id" value="{{ $userRole->role->id }}">
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip"
                                title="Удалить роль сотрудника">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </form>

                </td>

                @endcan

            </tr>


        @endforeach

        </tbody>

    </table>

    @can('create', \App\Models\UserRole::class)

        @if($user->id !== auth('web')->id())

            <div class="col-lg-12 my-4 text-right">
                <a href="{{route('admin.users.role.create', ['id' => $user->id])}}" class="btn btn-primary">
                    <i class="fa fa-plus"></i>&nbsp;
                    <span>Добавить роль сотруднику</span>
                </a>
            </div>

        @endif

    @endcan

</div>
