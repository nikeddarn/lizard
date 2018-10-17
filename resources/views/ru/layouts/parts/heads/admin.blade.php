{{-- Bootstrap meta--}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

{{--Page meta data--}}
<title>{{ config('app.name') }}</title>

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
<link href="/css/admin.css" rel="stylesheet">

<!-- include summernote rich text editor css-->
<link href="/css/summernote.css" rel="stylesheet">

<!-- bootstrap-touchspin -->
<link href="/css/jquery.bootstrap-touchspin.css" rel="stylesheet">




{{-- Application js file --}}
<script type="text/javascript" src="/js/app.js"></script>

<!-- include summernote rich text editor js-->
<script src="/js/summernote.js"></script>

<!-- bootstrap-touchspin -->
<script src="/js/jquery.bootstrap-touchspin.js"></script>



<script type="text/javascript" src="/js/lizard.js"></script>
<script type="text/javascript" src="/js/admin.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@yield('scripts')