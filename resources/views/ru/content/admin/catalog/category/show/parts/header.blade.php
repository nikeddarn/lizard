<div class="card card-body mb-4">

    <div class="d-flex justify-content-between">

        <div class="d-inline-flex">
            <div>
                <img class="table-image img-fluid img-thumbnail" src="{{ url('/storage/' . $category->image) }}"
                     alt="category icon">
            </div>
            <h1 class="h4 text-gray-hover ml-5">{{ $category->name }}</h1>
        </div>

        <div class="d-inline-flex justify-content-around align-items-start">

            <a href="{{ route('admin.categories.edit', ['id' => $category->id]) }}" data-toggle="tooltip"
               title="Редактировать" class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="edit"></i>
            </a>

            <form class="product-delete-form d-inline-block mx-1"
                  action="{{ route('admin.categories.destroy', ['id' => $category->id]) }}" method="post">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить категорию">
                    <i class="svg-icon-larger" data-feather="trash-2"></i>
                </button>
            </form>

            <a href="{{ route('admin.categories.index') }}" data-toggle="tooltip" title="К списку категорий"
               class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>

        </div>

    </div>

</div>
