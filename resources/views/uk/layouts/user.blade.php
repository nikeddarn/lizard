<!DOCTYPE html>
<html lang="uk">

<head>
    @include('layouts.parts.heads.common')
</head>

<body>

<div id="app" class="d-flex flex-column">

    <header>
        @include('layouts.parts.headers.common.index')
    </header>


    <main class="flex-fill">

        <div class="container">

            <div class="row">

                <div class="col-sm-4 col-md-3 my-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($user->avatar)
                            <img src="{{ url('/storage/' . $user->avatar) }}" width="120" alt="Аватар пользователя"
                                 class="rounded-circle mb-3">
                            @else
                                <img src="{{ url('/images/common/no_user_avatar.png') }}" width="120" alt="Нет аватара пользователя"
                                 class="rounded-circle mb-3">
                            @endif
                            <h5 class="bold mb-0">{{ $user->name }}</h5>
                        </div>

                        @include('layouts.parts.user.menu.index')

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

    {{-- Modal Main Menu --}}
    @include('layouts/parts/modals/modal_main_menu')

</div>

</body>
</html>
