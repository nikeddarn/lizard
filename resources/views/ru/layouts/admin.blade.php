<!DOCTYPE html>
<html lang="ru">

<head>
    @include('layouts.parts.heads.admin')
</head>

<body>

<div id="app" class="d-flex flex-column">

    @include('layouts.parts.headers.admin.index')


    <main class="flex-fill">

        <div class="container-fluid h-100">

            <div class="row my-4">

                <div class="col-lg-2 d-none d-lg-block">
                    @include('layouts.parts.admin.menu.index')
                </div>

                <div class="col-12 col-lg-10">
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

</body>
</html>
