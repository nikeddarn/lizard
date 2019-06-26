<!DOCTYPE html>
<html lang="uk">

<head>
    @include('layouts.parts.heads.admin')
</head>

<body>

<div id="app" class="container-fluid">

    @yield('content')

</div>

{{-- Yield custom page's scripts if exists--}}
@yield('scripts')

</body>
</html>
