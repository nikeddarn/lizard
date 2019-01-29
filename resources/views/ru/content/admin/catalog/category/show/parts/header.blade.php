<div class="row">

    <div class="col-auto d-none d-lg-block">
        <div class="card card-body">
            <img class="table-image img-thumbnail" src="{{ url('/storage/' . $category->image) }}"
                 alt="category icon">
        </div>
    </div>

    <div class="col">

        <div class="card card-body">

            <div class="d-flex justify-content-between">

                <h1 class="h4 text-gray-hover">{{ $category->name }}</h1>

                <div class="d-inline-flex align-items-start">

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

    </div>

</div>
