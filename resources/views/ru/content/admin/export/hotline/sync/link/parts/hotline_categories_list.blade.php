@foreach($dealerCategories as $dealerCategory)

    @if($dealerCategory->children->count())
        <li class="card my-1">
            <div class="row">
                <div class="col-auto">
                    <button class="btn btn-primary show-subcategory" data-toggle="collapse"
                            data-target="#category-{{ $dealerCategory->id }}">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                <div class="col">
                    <span>{{ $dealerCategory->name }}</span>
                </div>
            </div>

        </li>

    @else

        <li class="card my-1 pl-1">
            <div class="row">
                <div class="col">
                    <span>{{ $dealerCategory->name }}</span>
                </div>
                <div class="col-auto category-actions">

                            <form
                                class="ml-1" action="{{ route('admin.export.hotline.sync.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <input type="hidden" name="dealer_category_id"
                                       value="{{ $dealerCategory->id }}">
                                <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                        title="Подключить категорию">
                                    <i class="svg-icon-larger" data-feather="check-circle"></i>
                                </button>
                            </form>
                </div>
            </div>

        </li>

    @endif





    @if($dealerCategory->children->count())
        <li class="my-1">
            <div id="category-{{ $dealerCategory->id }}" class="collapse">
                <ul class="category-list">
                    @include('content.admin.export.hotline.sync.link.parts.hotline_categories_list', ['dealerCategories' => $dealerCategory->children,])
                </ul>
            </div>
        </li>
    @endif

@endforeach
