{{-- Bootstrap meta--}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

{{-- link favicon --}}
<link rel="shortcut icon" href="{{{ url('/images/common/icon.ico') }}}">

{{-- Seo alternate locales links--}}
@if(empty($noindexPage) && !empty($alternateLocalesLinks))
    @foreach($alternateLocalesLinks as $alternateLanguageLink)
        {!! $alternateLanguageLink !!}
    @endforeach
@endif

{{-- Seo pagination links--}}
@if(empty($noindexPage) && !empty($paginationLinks))
    @foreach($paginationLinks as $paginationLink)
        {!! $paginationLink !!}
    @endforeach
@endif

{{-- Robots meta --}}
@if(!empty($noindexPage))
    <meta name="robots" content="noindex,nofollow">
@endif

{{-- Canonical meta --}}
@if(empty($noindexPage) && !empty($metaCanonical))
    <link rel="canonical" href="{{ $metaCanonical }}">
@endif

@if(!empty($pageTitle))
    <title>{{ $pageTitle }}</title>
@endif

@if(!empty($pageDescription))
    <meta name="description" content="{{ $pageDescription }}">
@endif

@if(!empty($pageKeywords))
    <meta name="keywords" content="{{ $pageKeywords }}">
@endif

{{-- Laravel token--}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Yield custom page's styles if exists--}}
@yield('styles')

{{-- Application css file --}}
<link href="{{ url('/css/app.css') }}" rel="stylesheet">

{{-- Font-awesome--}}
<link href="{{ url('/css/font-awesome.min.css') }}" rel="stylesheet">


<!-- Custom css -->
<link href="{{ mix('css/lizard.css') }}" rel="stylesheet">


{{-- Application js file --}}
<script type="text/javascript" src="{{ url('/js/app.js') }}"></script>


<script type="text/javascript" src="{{ mix('js/lizard.js') }}"></script>

<script type="text/javascript" src="{{ url('/js/isotope.min.js') }}"></script>

<script src="{{ url('/js/feather.min.js') }}"></script>

<script>

    // append csrf to ajax headers
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        // replace feather icons with svg
        feather.replace();
    });

</script>

{{-- Yield custom page's scripts if exists--}}
@yield('scripts')
