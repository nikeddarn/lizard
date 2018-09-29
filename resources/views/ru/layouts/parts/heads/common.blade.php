{{-- Bootstrap meta--}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

{{--Page meta data--}}
<title>{{ isset($pageTitle)? $pageTitle : '' }}</title>
<meta name="description" content="{{ isset($pageDescription)? $pageDescription : '' }}">
<meta name="keywords" content="{{ isset($pageKeywords)? $pageKeywords : '' }}">

{{-- Laravel token--}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Yield page's sryles ans scripts if exists--}}
@yield('styles')

{{-- Application css file --}}
<link href="/css/app.css" rel="stylesheet">

{{-- Font-awesome--}}
<link href="/css/font-awesome.min.css" rel="stylesheet">


<!-- Custom css -->
<link href="/css/lizard.css" rel="stylesheet">



{{-- Application js file --}}
<script type="text/javascript" src="/js/app.js"></script>



<script type="text/javascript" src="/js/lizard.js"></script>

@yield('scripts')

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>