<div id="header-bottom" class="d-none d-md-block">
    <div class="container">
        <div class="row">
            <div class="col">
                <nav class="mega-menu">
                    <ul class="nav d-block">
                        <li class="dropdown mega-menu-dropdown d-block d-sm-inline-block">

                            {{-- Dropdown for xs --}}
                            <a href="#" class="d-block d-sm-none dropdown-toggle nav-link text-center active" data-toggle="dropdown">Каталог товаров</a>

                            {{-- Dropdown for sm+ --}}
                            <a href="#" class="d-none d-sm-block dropdown-toggle nav-link" data-toggle="dropdown">Каталог товаров</a>

                            {{-- Mega menu content --}}
                            <div class="dropdown-menu mega-menu-content p-2 p-lg-4" aria-labelledby="dropdownMenuButton">
                                @include('layouts.parts.headers.common.bottom.parts.category')
                            </div>

                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>