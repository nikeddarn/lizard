@if(!empty($availableLocalesLinksData))

    <nav id="headerTopPanelLanguage" class="nav">

        @foreach($availableLocalesLinksData as $locale => $localeData)

            @if($localeData['class'] === 'disabled')
                <a class="nav-link disabled">{{ $locale }}</a>
            @else
                <a class="nav-link {{ $localeData['class'] }}" href="{{ $localeData['url'] }}">{{ $locale }}</a>
            @endif

        @endforeach

    </nav>

@endif
