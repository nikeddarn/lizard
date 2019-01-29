<div class="card card-body">

    <div class="d-flex justify-content-between">

        <h1 class="h4 text-gray-hover">
            <span>Атрибут:</span>
            <span class="ml-4">{{ $attribute->name }}</span>
        </h1>

        <div class="d-inline-flex align-items-start">

            <a href="{{ route('admin.attributes.edit', ['id' => $attribute->id]) }}" data-toggle="tooltip"
               title="Редактировать" class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="edit"></i>
            </a>

            <form class="attribute-form d-inline-block mx-1"
                  action="{{ route('admin.attributes.destroy', ['id' => $attribute->id]) }}" method="post">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" value="{{ $attribute->id }}">

                <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                    <i class="svg-icon-larger" data-feather="trash-2"></i>
                </button>
            </form>

            <a href="{{ route('admin.attributes.index') }}" data-toggle="tooltip" title="К списку атрибутов"
               class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>

        </div>

    </div>

</div>
