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

        <div class="container">

            <div class="row">

                <div class="col-sm-4 col-md-3 page-content">

                    <div class="row">
                        <div class="col-lg-12">
                            @include('layouts.parts.user.avatar.index')
                        </div>
                        <div class="col-lg-12">
                            @include('layouts.parts.user.menu.index')
                        </div>
                    </div>

                </div>

                <div class="col-sm-8 col-md-9">

                    @yield ('content')

                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="footer">
        @include('layouts.parts.footers.common.index')
    </footer>


</div>

<!-- Scripts -->
@yield ('scripts')

</body>
</html>