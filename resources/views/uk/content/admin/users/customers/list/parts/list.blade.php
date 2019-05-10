<div class="table-responsive">
    <table class="table">

        <thead>
        <tr class="text-center">
            <td><strong>Создан</strong></td>
            <td class="d-none d-xl-table-cell text-center"><strong>Аватар</strong></td>
            <td><strong>Имя</strong></td>
            <td class="text-center"><strong>E-mail</strong></td>
            <td class="text-center"><strong>Телефон</strong></td>
            <td class="text-center"><strong>Баланс</strong></td>
            <td class="text-center"><strong>Прайс группа</strong></td>
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

                <td class="text-center">{{ $user->email }}</td>

                <td class="text-center">{{ $user->phone }}</td>

                <td class="text-center">{{ $user->balance }}</td>

                <td class="text-center user-price-group">{{ $user->price_group }}</td>

                <td class="user-actions d-flex justify-content-center align-items-start">

                    <a href="{{ route('admin.users.customers.show', ['id' => $user->id]) }}" data-toggle="tooltip"
                       title="Просмотреть" class="btn btn-primary">
                        <i class="svg-icon-larger" data-feather="eye"></i>
                    </a>

                    <form class="increase-price-group ml-1{{ $user->price_group < 3 ? ' d-inline-block' : ' d-none' }}"
                        action="{{ route('admin.users.group.up') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                title="Увеличить скидку (прайс колонку)">
                            <span>$</span>
                            <i class="svg-icon-larger" data-feather="arrow-down"></i>
                        </button>
                    </form>

                    <form class="decrease-price-group ml-1{{ $user->price_group > 1 ? ' d-inline-block' : ' d-none' }}"
                        action="{{ route('admin.users.group.down') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                title="Уменьшить скидку (прайс колонку)">
                            <span>$</span>
                            <i class="svg-icon-larger" data-feather="arrow-up"></i>
                        </button>
                    </form>

                    @if(Gate::allows('admins-edit'))
                        <a href="{{ route('admin.users.role.create', ['id' => $user->id]) }}" data-toggle="tooltip"
                           title="Создать сотрудника из пользователя" class="btn btn-primary make-employee ml-1">
                            <i class="svg-icon-larger" data-feather="user-check"></i>
                        </a>
                    @endif

                    <form class="customer-delete-form d-inline-block ml-1"
                          action="{{ route('admin.users.customers.destroy', ['id' => $user->id]) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="svg-icon-larger" data-feather="trash-2"></i>
                        </button>
                    </form>

                </td>

            </tr>

        @endforeach

        </tbody>
    </table>
</div>
