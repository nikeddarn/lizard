<ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link" href="{{ route('admin') }}">Обзор</a></li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#sidebar-catalog"  data-toggle="collapse" data-target="#sidebar-catalog">Каталог</a>
        <div class="collapse" id="sidebar-catalog" aria-expanded="false">
            <ul class="flex-column pl-3 nav">
                <li class="nav-item"><a class="nav-link py-1" href="{{ route('admin.categories.index') }}">Категории</a></li>
                <li class="nav-item"><a class="nav-link py-1" href="{{ route('admin.products.index') }}">Продукты</a></li>
                <li class="nav-item"><a class="nav-link py-1" href="{{ route('admin.attributes.index') }}">Атрибуты продуктов</a></li>
                <li class="nav-item"><a class="nav-link py-1" href="">Фильтры категорий</a></li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#submenu1" data-toggle="collapse" data-target="#submenu1">Reports</a>
        <div class="collapse" id="submenu1" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
                <li class="nav-item"><a class="nav-link py-0" href="">Orders</a></li>
                <li class="nav-item">
                    <a class="nav-link collapsed py-0" href="#submenu1sub1" data-toggle="collapse"
                       data-target="#submenu1sub1">Customers</a>
                    <div class="collapse small" id="submenu1sub1" aria-expanded="false">
                        <ul class="flex-column nav pl-4">
                            <li class="nav-item">
                                <a class="nav-link p-0" href="">
                                    <i class="fa fa-fw fa-clock-o"></i> Daily
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-0" href="">
                                    <i class="fa fa-fw fa-dashboard"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-0" href="">
                                    <i class="fa fa-fw fa-bar-chart"></i> Charts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-0" href="">
                                    <i class="fa fa-fw fa-compass"></i> Areas
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item"><a class="nav-link" href="#">Analytics</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Export</a></li>
</ul>