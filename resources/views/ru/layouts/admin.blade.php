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

            <div class="row h-100">

                <div class="col-6 col-md-3 col-lg-2 collapse d-md-flex bg-faded pt-2 pb-2 h-100" id="sidebar">
                    @include('layouts.parts.admin.menu.index')
                </div>

                <div class="col">
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