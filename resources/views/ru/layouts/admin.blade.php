<!DOCTYPE html>
<html lang="ru">

<head>
    @include('layouts.parts.heads.admin')
</head>

<body>

<div id="app" class="d-flex flex-column">

    <header>
        @include('layouts.parts.headers.admin.index')
    </header>


    <main class="flex-fill">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-4 col-md-3">

                    @include('layouts.parts.admin.menu.index')

                </div>

                <div class="col-sm-8 col-md-9 page-content">

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

<!-- Scripts -->
@yield ('scripts')

</body>
</html>