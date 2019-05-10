<!DOCTYPE html>
<html lang="uk">

<head>
    @include('layouts.parts.heads.main')
</head>

<body>

<div id="app" class="d-flex flex-column bg-white">

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

    {{-- Modal Main Menu --}}
    @include('layouts/parts/modals/modal_main_menu')


</div>

</body>
</html>
