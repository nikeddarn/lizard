<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title h4 text-center text-gray">Фільтри продуктів</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-0">
                <div id="product-filters">

                    @if(isset($usedFilters))
                        <div class="card pb-4">
                            <div class="card-header m-0">
                                <span class="main-link text-gray-hover">Застосовані фільтри</span>
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
                                <button class="btn btn-link nav-link text-gray text-left d-block w-100"
                                        data-toggle="collapse"
                                        aria-expanded="false"
                                        data-target="#filter-modal-{{ $filter->id }}" type="button"
                                        aria-controls="filter-modal-{{ $filter->id }}">{{ $filter->name }}</button>
                            </div>
                            <div id="filter-modal-{{ $filter->id }}" class="collapse">
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
            </div>
        </div>
    </div>
</div>
