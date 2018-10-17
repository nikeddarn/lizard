<form id="user-role-form" method="post" action="{{ route('admin.users.role.store') }}" role="form">

    @csrf

    <input type="hidden" name="users_id" value="{{ $user->id }}">

    <div class="card product-attribute-item p-5 mb-5">

        <div class="row form-group">
            <div class="col-sm-2">
                <label for="filter-select" class="required">Роль</label>
            </div>
            <div class="col-sm-8">
                <select id="filter-select" name="roles_id" class="w-100 selectpicker">
                    <option value="0" selected>Выберете роль</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <button type="submit" class="btn btn-primary">Добавить роль</button>

</form>