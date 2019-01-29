<div class="card card-body mb-4">

    <div class="d-flex justify-content-between">

        <h1 class="h4 text-gray-hover">Редактировать атрибут "{{ $attribute->name }}"</h1>

        <div class="d-flex">
            <button type="submit" form="attribute-form" data-toggle="tooltip" title="Сохранить"
                    class="btn btn-primary">
                <i class="svg-icon-larger" data-feather="save"></i>
            </button>
            <a href="{{ route('admin.attributes.index') }}" data-toggle="tooltip" title="Отменить"
               class="btn btn-primary ml-1">
                <i class="svg-icon-larger" data-feather="corner-up-left"></i>
            </a>
        </div>

    </div>

</div>
