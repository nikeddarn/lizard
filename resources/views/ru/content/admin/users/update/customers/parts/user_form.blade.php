<form id="filter-form" method="post" action="{{ route('admin.users.update', ['id' => $user->id]) }}" role="form">

    @csrf

    @method('PUT')

    <div class="card p-5 mb-5">

        <div class="row">
            <div class="col col-sm-2 col-md-3">
                <label class="required" for="price_group">Ценовая группа</label>
            </div>
            <div class="col-auto">
                <input id="price_group" name="price_group" type="number" required
                       value="{{ old('price_group', $user->price_group) }}" min="1" max="3">
            </div>
        </div>

    </div>

    <button type="submit" class="btn btn-primary">Сохранить изменения</button>

</form>
