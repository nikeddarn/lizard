@if(!empty($productGroups))

    <div class="card p-5 mb-5">

        <div class="alert alert-info mb-5">Перетягивайте мышкой, чтобы изменить порядок</div>

        <form method="post" action="{{ route('admin.content.main.sort.groups') }}" role="form">
            @csrf

            <div id="sortable-groups" class="row">
                @foreach($productGroups as $key => $group)
                    <div class="col-6 col-md-3">
                        <div class="group-item">
                            <div class="card card-body">
                                <div class="group-item-control">
                                    <a href="{{ route('admin.product.group.edit', ['group_id' => $group->id]) }}"
                                       class="btn btn-primary" title="Редактировать группу">
                                        <i class="svg-icon-larger" data-feather="edit"></i>
                                    </a>
                                    <button class="btn btn-danger" form="delete-group-form-{{ $group->id }}"
                                            type="submit"
                                            title="Удалить группу">
                                        <i class="svg-icon-larger" data-feather="x-circle"></i>
                                    </button>
                                </div>
                                <h5 class="mt-4 text-center">{{ $group->name_ru }}</h5>
                                <div class="p-2 text-center">
                                    <span>Категория:</span>
                                    <span class="ml-2">{{ $group->category->name_ru }}</span>
                                </div>
                                <div class="p-2 text-center">
                                    <span>Метод выборки:</span>
                                    <span class="ml-2">{{ $group->castProductMethod->name_ru }}</span>
                                </div>
                            </div>
                            <input type="hidden" name="group_id[]" value="{{ $group->id }}">
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="sort-group-button" class="text-right mt-5 d-none">
                <button type="submit" class="btn btn-primary">Изменить порядок сортировки</button>
            </div>

        </form>

        {{--delete slide forms--}}
        @foreach($productGroups as $group)
            <form id="delete-group-form-{{ $group->id }}" class="delete-group-form" method="post"
                  action="{{ route('admin.product.group.delete') }}"
                  role="form">
                @csrf
                <input type="hidden" name="group_id" value="{{ $group->id }}">
            </form>
        @endforeach


    </div>

@endif

<div class="text-right">
    <a href="{{ route('admin.product.group.create') }}" class="btn btn-primary">
        <i class="svg-icon-larger" data-feather="plus"></i>
        <span>Добавить группу товаров</span>
    </a>
</div>
