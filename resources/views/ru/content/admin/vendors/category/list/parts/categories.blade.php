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

                {{-- Category data with synchronized categories list --}}
                <div class="col">
                    @include('content.admin.vendors.category.list.parts.vendor_category')
                </div>

            </div>

        </li>

        {{-- Subcategory data --}}
        @include('content.admin.vendors.category.list.parts.subcategory')

    @else

        <li class="card my-1">

            <div class="row">

                {{-- Category data with synchronized categories list --}}
                <div class="col-12">

                    <div class="row">

                        <div class="col">
                            <span class="pl-5">{{ $category->name }}</span>
                        </div>

                        <div class="col-auto">
                            <a href="{{ route('vendor.category.sync', ['id' => $vendor->id, 'vendorOwnCategoryId' => $category->id]) }}"
                               data-toggle="tooltip"
                               title="Синхронизировать" class="btn btn-primary"><i class="fa fa-link"></i></a>
                        </div>

                    </div>

                </div>

                @if($vendorSynchronizedCategories->has($category->id) && $vendorSynchronizedCategories->get($category->id)->categories->count())

                    <div class="col-12 px-5 py-4">
                        @include('content.admin.vendors.category.list.parts.sync_categories')
                    </div>

                @endif


            </div>

        </li>

    @endif

@endforeach