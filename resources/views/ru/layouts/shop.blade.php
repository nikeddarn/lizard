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
        @include('elements.breadcrumbs.index')

    <main class="flex-fill">

        <div class="container">

            @yield('content')

        </div>

    </main>

    <!-- Footer -->
    <footer class="footer">
        @include('layouts.parts.footers.common.index')
    </footer>


</div>

</body>
</html>