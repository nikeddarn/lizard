<div class="card card-body mb-4">

    <div class="d-flex justify-content-between">

            <h1 class="h4 text-gray-hover ml-5">{{ $virtualCategory->name_ru }}</h1>

        <div class="d-inline-flex justify-content-around align-items-start">

            <a href="{{ route('admin.categories.virtual.edit', ['id' => $virtualCategory->id]) }}" data-toggle="tooltip"
               title="Редактировать" class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="edit"></i>
            </a>

            <form class="product-delete-form d-inline-block mx-1"
                  action="{{ route('admin.categories.virtual.destroy', ['id' => $virtualCategory->id]) }}" method="post">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить категорию">
                    <i class="svg-icon-larger" data-feather="trash-2"></i>
                </button>
            </form>

            <a href="{{ route('admin.categories.virtual.index') }}" data-toggle="tooltip" title="К списку категорий"
               class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>

        </div>

    </div>

</div>
