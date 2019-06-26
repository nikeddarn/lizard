<!DOCTYPE html>
<html lang="uk">

<head>
    @include('layouts.parts.heads.admin')
</head>

<body>

<div id="app" class="d-flex flex-column">

    @include('layouts.parts.headers.admin.index')


    <main class="flex-fill">

        <div class="container-fluid h-100">

            <div class="row my-4">

                <div class="col-lg-3 col-xl-2 d-none d-lg-block">
                    @include('layouts.parts.admin.menu.index')
                </div>

                <div class="col-12 col-lg-9 col-xl-10">
                    @yield ('content')
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="footer">
        @include('layouts.parts.footers.admin.index')
    </footer>


</div>

{{-- Yield custom page's scripts if exists--}}
@yield('scripts')

</body>
</html>
