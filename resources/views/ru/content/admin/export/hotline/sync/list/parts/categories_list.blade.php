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
            </div>

        </li>

    @else

        <li class="card my-1 pl-1">
            <div class="row">
                <div class="col">
                    <span>{{ $category->name }}</span>
                </div>

                @if($category->dealerCategories->count())
                    <div class="col-auto ml-auto">
                        <span>{{ $category->dealerCategories->first()->name }}</span>
                    </div>
                @endif

                <div class="col-auto category-actions">
                    @if($category->isLeaf())

                        @if($category->dealerCategories->count())
                            <form
                                class="category-publish-off-form ml-1 {{ $category->dealerCategories->first()->pivot->published ? 'd-inline-block' : 'd-none' }}"
                                action="{{ route('admin.export.hotline.sync.publish.off') }}" method="post">
                                @csrf
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <input type="hidden" name="dealer_category_id"
                                       value="{{ $category->dealerCategories->first()->id }}">
                                <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                        title="Выключить публикацию категории">
                                    <i class="svg-icon-larger" data-feather="check-circle"></i>
                                </button>
                            </form>

                            <form
                                class="category-publish-on-form ml-1 {{ $category->dealerCategories->first()->pivot->published ? 'd-none' : 'd-inline-block' }}"
                                action="{{ route('admin.export.hotline.sync.publish.on') }}" method="post">
                                @csrf
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <input type="hidden" name="dealer_category_id"
                                       value="{{ $category->dealerCategories->first()->id }}">
                                <button type="submit" class="btn btn-primary btn-outline-theme" data-toggle="tooltip"
                                        title="Включить публикацию категории">
                                    <i class="svg-icon-larger" data-feather="check-circle"></i>
                                </button>
                            </form>

                            <a href="{{ route('admin.export.hotline.products.list', ['category_id' => $category->id]) }}"
                               data-toggle="tooltip"
                               title="Выбрать продукты для экспорта" class="btn btn-primary">
                                <i class="svg-icon-larger" data-feather="list"></i>
                            </a>

                            <form class="d-inline-block category-form ml-lg-2"
                                  action="{{ route('admin.export.hotline.sync.delete') }}" method="post">
                                @csrf
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <input type="hidden" name="dealer_category_id"
                                       value="{{ $category->dealerCategories->first()->id }}">
                                <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Удалить">
                                    <i class="svg-icon-larger" data-feather="link-2"></i>
                                </button>
                            </form>

                        @else
                            <a href="{{ route('admin.export.hotline.sync.create', ['category_id' => $category->id]) }}"
                               data-toggle="tooltip"
                               title="Подключить категорию" class="btn btn-primary">
                                <i class="svg-icon-larger" data-feather="link"></i>
                            </a>
                        @endif

                    @endif
                </div>
            </div>

        </li>

    @endif





    @if($category->children->count())
        <li class="my-1">
            <div id="category-{{ $category->id }}" class="collapse">
                <ul class="category-list">
                    @include('content.admin.export.hotline.sync.list.parts.categories_list', ['categories' => $category->children,])
                </ul>
            </div>
        </li>
    @endif

@endforeach
