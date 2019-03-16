<div class="card card-body mb-4">

    <div class="row d-flex justify-content-between align-items-start">

        <div class="col-auto  d-flex justify-content-start align-items-center">
            <div class="row">
                <div class="col-12 col-sm-auto">
                    <h1 class="h4 text-gray-hover">
                        <span>Пользователь:</span>
                        <span class="ml-1 ml-md-4">{{ $user->name }}</span>
                    </h1>
                </div>

                <div class="col-12 col-sm-auto d-flex align-items-center text-gray-hover">
                    (Прайс группа
                    <span class="user-price-group ml-1 ml-md-2">{{ $user->price_group }}</span>
                    )
                </div>
            </div>
        </div>

        <div class="col-auto user-actions d-flex justify-content-center align-items-start">

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

        </div>

    </div>

</div>
