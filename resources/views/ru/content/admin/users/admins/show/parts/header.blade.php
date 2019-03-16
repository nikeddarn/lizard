<div class="card card-body mb-4">

    <div class="d-flex justify-content-between align-items-start">

        <h1 class="h4 text-gray-hover">
            <span>Сотрудник:</span>
            <span class="ml-1 ml-md-4">{{ $user->name }}</span>
        </h1>

        <div class="d-flex justify-content-center align-items-start">

            @can('modify', $user)
                <form class="customer-delete-form d-inline-block ml-1"
                      action="{{ route('admin.users.administrators.destroy', ['id' => $user->id]) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                        <i class="svg-icon-larger" data-feather="trash-2"></i>
                    </button>
                </form>
            @endcan

        </div>

    </div>

</div>
