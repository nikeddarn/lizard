{{-- Mega menu for lg devices--}}

<div class="d-none d-lg-block container-fluid">
    <div class="row h-100 no-gutters">

        <div class="col-lg-3">
            <div class="nav nav-pills h-100" id="v-pills-categories-tabs" role="tablist"
                 aria-orientation="vertical">

                <ul class="w-100">
                    @foreach($megaMenuCategories as $key => $category)
                        <li>
                            @if($category->isLeaf())
                                <a class="text-gray d-block px-2 py-1 {{($key === 0)? ' show active' : ''}}"
                                   href="{{ route('shop.category.leaf.index', ['url' => $category->url]) }}">{{ $category->name }}</a>
                            @else
                                <a class="text-gray d-block px-2 py-1 {{($key === 0)? ' active' : ''}}"
                                   data-toggle="pill"
                                   href="#v-pills-{{ $category->id }}" role="tab"
                                   aria-controls="v-pills-{{ $category->id }}"
                                   aria-selected="false">{{ $category->name }}
                                    <span class="float-right"><i class="fa fa-chevron-right"></i></span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>

        <div class="col-lg-9">
            <div class="tab-content h-100 py-2 px-3" id="v-pills-categoriesContent">

                @foreach($megaMenuCategories as $key => $category)
                    <div class="tab-pane fade{{($key === 0)? ' show active' : ''}}" id="v-pills-{{ $category->id }}"
                         role="tabpanel"
                         aria-labelledby="v-pills-{{ $category->id }}-tab">
                        @if($category->children->count())
                            @include('layouts.parts.headers.common.bottom.parts.subcategory', ['subcategories' => $category->children])
                        @endif
                    </div>

                @endforeach

            </div>
        </div>

    </div>
</div>

{{-- Mega menu for xs to md devices--}}

<div class="d-block d-lg-none container-fluid">

    <div class="row h-100">

        @foreach($megaMenuCategories as $key => $category)

            <div class="col-xs-12 col-sm-6 col-md-4 mb-2">
                <a class="text-gray d-block" href="{{ route('shop.category.index', $category->url) }}">{{ $category->name }}</a>
            </div>

        @endforeach

    </div>

</div>