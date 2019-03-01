@foreach($categories as $category)

    @if($category->children->count())

        <li class="card my-1">

            <div class="row">

                {{-- Open subcategory button --}}
                <div class="col-auto">
                    <button class="btn btn-primary show-subcategory" data-toggle="collapse"
                            data-target="#category-{{ $category->id }}">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </div>

                {{-- Category data  --}}
                <div class="col">{{ $category->name }}</div>

            </div>

        </li>

        {{-- Subcategory data --}}
        @include('content.admin.vendors.catalog.tree.parts.subcategory')

    @else

        <li class="card my-1">

            <div class="row">

                {{-- Category data  --}}
                <div class="col-12">

                    <div class="row">

                        <div class="col">
                            <span class="pl-5">{{ $category->name }}</span>
                        </div>

                        <div class="col-auto d-flex justify-content-center align-items-start">
                            <a href="{{ route('vendor.catalog.category.products', ['id' => $vendor->id, 'vendorOwnCategoryId' => $category->id]) }}"
                               data-toggle="tooltip"
                               title="Смотреть товары" class="btn btn-primary">
                                <i class="svg-icon-larger" data-feather="list"></i>
                            </a>

                            @if($synchronizedVendorCategories->get($category->id))
                                <a href="{{ route('vendor.category.show', ['vendorCategoriesId' => $synchronizedVendorCategories->get($category->id)->id]) }}"
                                   data-toggle="tooltip"
                                   title="Смотреть загруженную категорию" class="btn btn-primary ml-1">
                                    <i class="svg-icon-larger" data-feather="eye"></i>
                                </a>

                                <form class="delete-form" method="post" action="{{ route('vendor.category.delete') }}">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="vendorCategoriesId"
                                           value="{{ $synchronizedVendorCategories->get($category->id)->id }}">
                                    <button type="submit"
                                            data-toggle="tooltip"
                                            title="Удалить категорию и все товары" class="btn btn-danger ml-1">
                                        <i class="svg-icon-larger" data-feather="link-2"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('vendor.category.create', ['id' => $vendor->id, 'vendorOwnCategoryId' => $category->id]) }}"
                                   data-toggle="tooltip"
                                   title="Синхронизировать" class="btn btn-primary ml-1">
                                    <i class="svg-icon-larger" data-feather="link"></i>
                                </a>
                            @endif
                        </div>

                    </div>

                </div>

            </div>

        </li>

    @endif

@endforeach
