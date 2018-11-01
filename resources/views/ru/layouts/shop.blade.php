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

    {{-- breadcrumbs --}}
    @yield('breadcrumbs')

    <main class="flex-fill">

        <div class="container">

            @yield('content')

        </div>

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