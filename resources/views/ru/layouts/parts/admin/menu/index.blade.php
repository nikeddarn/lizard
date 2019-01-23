<div id="admin-main-menu">

    {{-- Overview--}}
    <div class="card">
        <div class="card-header text-gray text-left m-0 p-0 pr-2">
            <a href="{{ route('admin') }}" class="btn btn-link nav-link text-gray text-left d-block w-100">Обзор</a>
        </div>
    </div>

    {{-- Shop--}}
    <div class="card">
        <div class="card-header m-0 p-0">
            <button class="btn btn-link nav-link text-gray text-left d-block w-100" data-toggle="collapse"
                    aria-expanded="false"
                    data-target="#main-menu-shop" type="button"
                    aria-controls="main-menu-shop">Магазин
            </button>
        </div>
        <div id="main-menu-shop" class="collapse">
            <div class="card-body py-1">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link py-1" href="{{ route('admin.categories.index') }}">Категории</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1" href="{{ route('admin.categories.virtual.index') }}">Виртуальные категории</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1" href="{{ route('admin.products.index') }}">Продукты</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1" href="{{ route('admin.attributes.index') }}">Атрибуты
                            продуктов</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Vendors --}}
    <div class="card">
        <div class="card-header m-0 p-0">
            <button class="btn btn-link nav-link text-gray text-left d-block w-100" data-toggle="collapse"
                    aria-expanded="false"
                    data-target="#main-menu-vendors" type="button"
                    aria-controls="main-menu-vendors">Поставщики
            </button>
        </div>
        <div id="main-menu-vendors" class="collapse">

                {{-- Vendors List --}}
                @foreach($vendors as $vendor)
                    <div class="card">
                        <div class="card-header m-0 p-0">
                            <button class="btn btn-link nav-link text-gray text-left d-block w-100 pl-5"
                                    data-toggle="collapse"
                                    aria-expanded="false"
                                    data-target="#main-menu-vendors-{{ $vendor->id }}" type="button"
                                    aria-controls="main-menu-vendors-{{ $vendor->id }}">{{ $vendor->name }}
                            </button>
                        </div>
                        <div id="main-menu-vendors-{{ $vendor->id }}" class="collapse">
                            <div class="card-body py-1 pl-5">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link py-1"
                                           href="{{ route('vendor.categories.index', ['vendorId' => $vendor->id]) }}">Категории</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach

        </div>
    </div>

    {{-- Sync--}}
    <div class="card">
        <div class="card-header m-0 p-0">
            <button class="btn btn-link nav-link text-gray text-left d-block w-100" data-toggle="collapse"
                    aria-expanded="false"
                    data-target="#main-menu-sync" type="button"
                    aria-controls="main-menu-sync">Синхронизация
            </button>
        </div>
        <div id="main-menu-sync" class="collapse">
            <div class="card-body py-1">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link py-1" href="{{ route('vendor.categories.synchronized') }}">Категории</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1" href="{{ route('vendor.synchronization.index') }}">Задания</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Users--}}
    <div class="card">
        <div class="card-header m-0 p-0">
            <button class="btn btn-link nav-link text-gray text-left d-block w-100" data-toggle="collapse"
                    aria-expanded="false"
                    data-target="#main-menu-users" type="button"
                    aria-controls="main-menu-users">Пользователи
            </button>
        </div>
        <div id="main-menu-users" class="collapse">
            <div class="card-body py-1">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link py-1" href="{{ route('admin.users.administrators') }}">Сотрудники</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1" href="{{ route('admin.users.customers') }}">Покупатели</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
