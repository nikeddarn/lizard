<!DOCTYPE html>
<html lang="ru">

<head>
    @include('layouts.parts.heads.common')
</head>

<body>

<div id="app" class="d-flex flex-column">

    <header>
        {{-- Header--}}
        @include('layouts.parts.headers.common.index')
    </header>


    <main class="flex-fill">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        @include('layouts.parts.footers.common.index')
    </footer>


</div>

</body>
</html>