<div id="product-filters">

    @if(isset($usedFilters))
        <div class="card pb-4">
            <div class="card-header m-0">
                <span class="main-link text-gray-hover">Примененные фильтры</span>
            </div>
            <div class="card-body px-1 py-0">
                <ul class="nav flex-column px-0">
                    @foreach($usedFilters as $filter)
                        <li class="nav-item">
                            <a class="nav-link text-gray d-flex align-items-center justify-content-between py-1"
                               href="{{ $filter->href }}">
                                <span>{{ $filter->name }}</span>
                                <i class="svg-icon-larger" data-feather="x-circle"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    @endif

    @foreach($filters as $filter)
        <div class="card">
            <div class="card-header m-0 p-0 pr-2">
                <button class="btn btn-link nav-link text-gray text-left d-block w-100" data-toggle="collapse"
                        aria-expanded="{{ $filter->opened ? 'true' : 'false' }}"
                        data-target="#filter-{{ $filter->id }}" type="button"
                        aria-controls="filter-{{ $filter->id }}">{{ $filter->name }}</button>
            </div>
            <div id="filter-{{ $filter->id }}" class="collapse{{ $filter->opened ? ' show' : '' }}">
                <div class="card-body py-0">
                    @foreach($filter->attributeValues as $attributeValue)
                        <a href="{{ $attributeValue->href }}" class="filter-sidebar-checkbox">
                            <div class="checkbox">
                                <label
                                    @if($attributeValue->checked) class="filter-sidebar-checkbox-checked" @endif> {{ $attributeValue->value }}</label>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

</div>
