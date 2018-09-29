<div class="row justify-content-between">

    <div class="col-6 col-md-3">
        <nav class="nav">
            <a href="" data-target="#sidebar" data-toggle="collapse" class="navbar-link d-block d-md-none"><i
                        class="fa fa-bars"></i></a>
        </nav>
    </div>

    <div class="col-6 col-md-3">
        <nav class="nav">
            <a class="float-right" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выйти&nbsp;
                <i class="fa fa-sign-out"></i>
            </a>
        </nav>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>