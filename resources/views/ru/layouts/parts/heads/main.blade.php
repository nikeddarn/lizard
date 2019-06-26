<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-41789543-2"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-41789543-2');
</script>


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

{{-- Yield custom page's meta section if exists--}}
@yield('meta')

{{-- Yield custom page's styles section if exists--}}
@yield('styles')

{{-- Application css file --}}
<link ref="preload" href="{{ url('/css/app.css') }}" rel="stylesheet">


<!-- Custom css -->
<link ref="preload" href="{{ mix('css/lizard.css') }}" rel="stylesheet">
