<div>
    <ul class="nav">
        <li class="nav-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-link" type="submit">
                    <i class="svg-icon-larger text-lizard" data-feather="log-out"></i>
                    <span class="ml-1 text-lizard">Выйти</span>
                </button>
            </form>
        </li>
    </ul>
</div>


