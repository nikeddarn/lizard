<div class="dropdown dropdown-hover d-inline-block mr-3">

    <button class="btn btn-light btn-sm border rounded-pill dropdown-toggle caret-off" type="button"
            id="sortProductByDropdownMenu" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <span class="text-gray">{{ trans("shop.sort_products_method.$sortProductsMethod") }}
            <i class="svg-icon" data-feather="chevron-down"></i>
        </span>
    </button>

    <div class="dropdown-menu dropdown-menu-right shadow-sm m-0" aria-labelledby="sortProductByDropdownMenu">
        @if(count($sortProductsUrls) > 1)
            @foreach($sortProductsUrls as $sortMethod => $sortMethodData)
                @if($sortMethodData['class'] === 'disabled')
                    <span
                        class="dropdown-item cursor-pointer px-3">{{ trans("shop.sort_products_method.$sortMethod") }}</span>
                @else
                    <a class="dropdown-item px-3 {{ $sortMethodData['class'] }}"
                       href="{{ $sortMethodData['url'] }}">{{ trans("shop.sort_products_method.$sortMethod") }}</a>
                @endif
            @endforeach
        @endif
    </div>

</div>

@if(count($showProductsUrls) > 1)
    @foreach($showProductsUrls as $showMethod => $showMethodData)
        @if($showMethodData['class'] === 'disabled')
            <span href="{{ $showMethodData['url'] }}"
                  class="btn btn-icon rounded-pill btn-sm btn-primary ml-1"
                  title="{{ trans("shop.show_products_method.$showMethod") }}">
                <i class="{{ $showMethodData['icon'] }}"></i>
            </span>
        @else
            <a href="{{ $showMethodData['url'] }}"
               class="btn btn-icon rounded-pill btn-sm btn-primary btn-outline-theme ml-1"
               title="{{ trans("shop.show_products_method.$showMethod") }}">
                <i class="{{ $showMethodData['icon'] }}"></i>
            </a>
        @endif
    @endforeach
@endif
