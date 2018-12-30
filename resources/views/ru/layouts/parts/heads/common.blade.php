{{-- Bootstrap meta--}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

{{--Page meta data--}}

@if(isset($canonicalUrl))
    <link rel="canonical" href="{{ $canonicalUrl }}">
@endif

@if(isset($pageTitle))
    <title>{{ $pageTitle }}</title>
@endif

@if(isset($pageDescription))
    <meta name="description" content="{{ $pageDescription }}">
@endif

@if(isset($pageKeywords))
    <meta name="keywords" content="{{ $pageKeywords }}">
@endif

@if(isset($pageRobots))
    <meta name="robots" content="{{ $pageRobots }}">
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

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

{{-- Yield custom page's scripts if exists--}}
@yield('scripts')
