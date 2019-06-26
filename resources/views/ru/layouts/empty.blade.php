<!DOCTYPE html>
<html lang="ru">

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
