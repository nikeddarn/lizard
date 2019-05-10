<!DOCTYPE html>
<html lang="uk">

<head>
    @include('layouts.parts.heads.common')
</head>

<body>

<div id="app" class="d-flex flex-column">

    <header>
        {{-- Header--}}
        @include('layouts.parts.headers.common.index')


    </header>

    <main class="flex-fill mb-4">

        <div class="container">

            {{-- Breadcrumbs--}}
            <div class="card card-body my-2 py-2">
                @include('layouts.parts.breadcrumbs.shop.index')
            </div>

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
