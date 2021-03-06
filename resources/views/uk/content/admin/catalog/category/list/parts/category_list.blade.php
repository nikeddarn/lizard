@foreach($categories as $category)

    @if($category->children->count())
        <li class="card my-1">
            <div class="row">
                <div class="col-auto">
                    <button class="btn btn-primary show-subcategory" data-toggle="collapse"
                            data-target="#category-{{ $category->id }}">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                <div class="col">
                    <span>{{ $category->name }}</span>
                </div>
                <div class="col-auto">

                    <a href="{{ route('admin.categories.up', ['id' => $category->id]) }}" data-toggle="tooltip"
                       title="Переместить выше" class="btn btn-primary">
                        <i class="svg-icon-larger" data-feather="arrow-up"></i>
                    </a>

                    <a href="{{ route('admin.categories.down', ['id' => $category->id]) }}" data-toggle="tooltip"
                       title="Переместить ниже" class="btn btn-primary">
                        <i class="svg-icon-larger" data-feather="arrow-down"></i>
                    </a>

                    <a href="{{ route('admin.categories.show', ['id' => $category->id]) }}" data-toggle="tooltip"
                       title="Просмотреть" class="btn btn-primary">
                        <i class="svg-icon-larger" data-feather="eye"></i>
                    </a>

                    <a href="{{ route('admin.categories.edit', ['id' => $category->id]) }}" data-toggle="tooltip"
                       title="Редактировать" class="btn btn-primary">
                        <i class="svg-icon-larger" data-feather="edit"></i>
                    </a>

                    <form class="d-inline-block category-form ml-lg-2"
                          action="{{ route('admin.categories.destroy', ['id' => $category->id]) }}" method="post" data-check-empty-url="{{ route('admin.categories.empty', ['id' => $category->id]) }}">
                        @csrf
                        <input type="hidden" name="_method" value="delete"/>
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="svg-icon-larger" data-feather="trash-2"></i>
                        </button>
                    </form>
                </div>
            </div>

        </li>

    @else

        <li class="card my-1 pl-1">
            <div class="row">
                <div class="col">
                    <span
                        class="d-none d-lg-inline-block float-right px-1">Продуктов:&nbsp;{{ $category->products_count }}</span>
                    <span>{{ $category->name }}</span>
                </div>
                <div class="col-auto">

                    <a href="{{ route('admin.categories.up', ['id' => $category->id]) }}" data-toggle="tooltip"
                       title="Переместить выше" class="btn btn-primary">
                        <i class="svg-icon-larger" data-feather="arrow-up"></i>
                    </a>

                    <a href="{{ route('admin.categories.down', ['id' => $category->id]) }}" data-toggle="tooltip"
                       title="Переместить ниже" class="btn btn-primary">
                        <i class="svg-icon-larger" data-feather="arrow-down"></i>
                    </a>

                    <a href="{{ route('admin.categories.show', ['id' => $category->id]) }}" data-toggle="tooltip"
                       title="Просмотреть" class="btn btn-primary">
                        <i class="svg-icon-larger" data-feather="eye"></i>
                    </a>

                    <a href="{{ route('admin.categories.edit', ['id' => $category->id]) }}" data-toggle="tooltip"
                       title="Редактировать" class="btn btn-primary">
                        <i class="svg-icon-larger" data-feather="edit"></i>
                    </a>

                    <form class="d-inline-block category-form ml-lg-2"
                          action="{{ route('admin.categories.destroy', ['id' => $category->id]) }}" method="post" data-check-empty-url="{{ route('admin.categories.empty', ['id' => $category->id]) }}">
                        @csrf
                        <input type="hidden" name="_method" value="delete"/>
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                            <i class="svg-icon-larger" data-feather="trash-2"></i>
                        </button>
                    </form>
                </div>
            </div>

        </li>

    @endif





    @if($category->children->count())
        <li class="my-1">
            <div id="category-{{ $category->id }}" class="collapse">
                <ul class="category-list">
                    @include('content.admin.catalog.category.list.parts.category_list', ['categories' => $category->children,])
                </ul>
            </div>
        </li>
    @endif

@endforeach
