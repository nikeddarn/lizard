<a class="d-none d-sm-inline-block" href="{{ route('main', ['locale' => app()->getLocale() === config('app.canonical_locale') ? '' : app()->getLocale()]) }}">
    <img src="{{ url('/images/common/logo_small.png') }}" class="img-responsive"
         data-text-logo="{{ config('app.name') }}" alt="logotype">
</a>

<a id="mobile-logotype-text" class="d-inline-block d-sm-none ml-2" href="{{ route('main') }}">Lizard</a>
