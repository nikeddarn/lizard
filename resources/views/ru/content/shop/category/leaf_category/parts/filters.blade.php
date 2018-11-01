<button id="filters-toggle" class="btn btn-block dropdown-toggle d-md-none my-4 h6" type="button" data-toggle="collapse"
        data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">Фильтровать товары
</button>

<div class="collapse d-md-block my-md-4" id="collapseFilter">

    @if(isset($usedFilters))

        <div class="mb-4">

            <!-- Title -->
            <div class="underlined-title">
                <h4 class="page-header text-gray-lighter h5">Примененные фильтры</h4>
            </div>

            <ul class="nav flex-column">
                @foreach($usedFilters as $filter)
                    <li class="nav-item">
                        <span class="nav-link text-gray">{{ $filter->name }}
                            <a href="{{ $filter->href }}" class="float-right text-gray-lighter">
                                <i class="fa fa-close fa-lg"></i>
                            </a>
                        </span>
                    </li>
                @endforeach
            </ul>

        </div>

    @endif

    @foreach($filters as $filter)

        <div class="mb-4">

            <!-- Title -->
            <div class="underlined-title">
                <h4 class="page-header text-gray-lighter h5">{{ $filter->name }}</h4>
            </div>

            <div>
                @foreach($filter->attributeValues as $attributeValue)
                    <a href="{{ $attributeValue->href }}" class="filter-sidebar-checkbox">
                        <div class="checkbox">
                            <label @if($attributeValue->checked) class="filter-sidebar-checkbox-checked" @endif> {{ $attributeValue->value }}</label>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>

    @endforeach

</div>