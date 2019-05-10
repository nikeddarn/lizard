<div>
    <ul class="nav">
        <li class="nav-item d-flex justify-content-between align-items-center">
            <span>{{ $user->name }}</span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-link ml-1" type="submit" title="Выйти">
                    <i class="svg-icon-larger" data-feather="log-out"></i>
                </button>
            </form>
        </li>
    </ul>
</div>


