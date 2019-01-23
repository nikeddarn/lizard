@if(count($availableLocalesLinksData) > 1)

    <nav class="nav nav-lang">

        @foreach($availableLocalesLinksData as $locale => $localeUrl)

            @if($locale === app()->getLocale())
                <a class="nav-link disabled active cursor-pointer px-1 py-1">{{ trans("shop.locale.$locale") }}</a>
            @else
                <a class="nav-link px-1 py-1" href="{{ $localeUrl }}">{{ trans("shop.locale.$locale") }}</a>
            @endif

                @if(!$loop->last)
                    <a class="nav-link pipe px-1 py-1">|</a>
                @endif

        @endforeach

    </nav>

@endif
