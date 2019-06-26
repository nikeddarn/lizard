{{-- Bootstrap meta--}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

{{-- link favicon --}}
<link rel="shortcut icon" href="{{{ url('/images/common/icon.ico') }}}">

{{--Page meta data--}}
<title>{{ config('app.name') }} - Администрирование</title>

{{-- Laravel token--}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Yield page's styles and scripts if exists--}}
@yield('styles')

{{-- Application css file --}}
<link href="{{ url('/css/app.css') }}" rel="stylesheet">

{{-- Font-awesome--}}
<link href="{{ url('/css/font-awesome.min.css') }}" rel="stylesheet">


<!-- Custom css -->
<link href="{{ mix('css/lizard.css') }}" rel="stylesheet">
<link href="{{ mix('css/admin.css') }}" rel="stylesheet">

<!-- include summernote rich text editor css-->
<link href="{{ url('/css/summernote.css') }}" rel="stylesheet">

<!-- bootstrap-touchspin -->
<link href="{{ url('/css/jquery.bootstrap-touchspin.css') }}" rel="stylesheet">




{{-- Application js file --}}
<script type="text/javascript" src="{{ url('/js/app.js') }}"></script>

<!-- include summernote rich text editor js-->
<script src="{{ url('/js/summernote.js') }}"></script>

<!-- bootstrap-touchspin -->
<script src="{{ url('/js/jquery.bootstrap-touchspin.js') }}"></script>


<script src="{{ url('/js/feather.min.js') }}"></script>

<script type="text/javascript" src="{{ mix('js/admin.js') }}"></script>

<script>
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
