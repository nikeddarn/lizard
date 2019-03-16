<div id="admin-main-menu" class="card card-body">

    <ul class="nav">

        {{-- Overview--}}
        <li class="nav-item w-100">
            <a href="{{ route('admin.overview') }}"
               class="nav-link text-gray pl-0">Обзор</a>
        </li>

        {{-- Shop--}}
        @if(Gate::allows('local-catalog-show'))
            <li class="nav-item w-100">

                <a class="nav-link text-gray position-relative pl-0 pr-4" data-toggle="collapse" aria-expanded="false"
                   data-target="#main-menu-shop" aria-controls="main-menu-shop">Каталог</a>

                <ul id="main-menu-shop" class="nav collapse">

                    {{-- Categories --}}
                    <li class="nav-item w-100">
                        <a id="main-menu-shop-categories" href="{{ route('admin.categories.index') }}"
                           class="nav-link text-gray pl-4 submenu-link">Категории</a>
                    </li>

                    {{-- Virtual categories --}}
                    <li class="nav-item w-100">
                        <a id="main-menu-shop-virtual-categories" href="{{ route('admin.categories.virtual.index') }}"
                           class="nav-link text-gray pl-4 submenu-link">Виртуальные категории</a>
                    </li>

                    {{-- Products --}}
                    <li class="nav-item w-100">
                        <a id="main-menu-shop-products" href="{{ route('admin.products.index') }}"
                           class="nav-link text-gray pl-4 submenu-link">Продукты</a>
                    </li>

                    {{-- Attributes --}}
                    <li class="nav-item w-100">
                        <a id="main-menu-shop-attributes" href="{{ route('admin.attributes.index') }}"
                           class="nav-link text-gray pl-4 submenu-link">Атрибуты</a>
                    </li>

                </ul>

            </li>
        @endif

        {{-- Vendors --}}
        @if(Gate::allows('vendor-catalog'))
            <li class="nav-item w-100">

                <a class="nav-link text-gray position-relative pl-0 pr-4" data-toggle="collapse" aria-expanded="false"
                   data-target="#main-menu-vendors" aria-controls="main-menu-vendors">Поставщики</a>

                <ul id="main-menu-vendors"
                    class="nav collapse">

                    @foreach($vendors as $vendor)
                        <li class="nav-item w-100">

                            <a class="nav-link text-gray position-relative pl-4 pr-4" data-toggle="collapse"
                               aria-expanded="false" data-target="#main-menu-vendors-{{ $vendor->id }}"
                               aria-controls="main-menu-vendors-{{ $vendor->id }}">{{ $vendor->name }}</a>

                            <ul id="main-menu-vendors-{{ $vendor->id }}" class="nav collapse">

                                {{-- Categories tree --}}
                                <li class="nav-item w-100">
                                    <a id="main-menu-vendors-{{ $vendor->id }}-categories-tree"
                                       href="{{ route('vendor.catalog.categories.tree', ['vendorId' => $vendor->id]) }}"
                                       class="nav-link text-gray pl-5 submenu-link">Дерево категорий</a>
                                </li>

                                {{-- Downloaded categories --}}
                                <li class="nav-item w-100">
                                    <a id="main-menu-vendors-{{ $vendor->id }}-categories-downloaded"
                                       href="{{ route('vendor.category.list', ['vendorId' => $vendor->id]) }}"
                                       class="nav-link text-gray pl-5 submenu-link">Загруженные категории</a>
                                </li>

                            </ul>

                        </li>
                    @endforeach

                </ul>

            </li>
        @endif

        {{-- Sync--}}
        @if(Gate::allows('vendor-catalog'))
        <li class="nav-item w-100">

            <a class="nav-link text-gray position-relative pl-0 pr-4" data-toggle="collapse" aria-expanded="false"
               data-target="#main-menu-sync" aria-controls="main-menu-sync">Синхронизация</a>

            <ul id="main-menu-sync" class="nav collapse">

                {{--Synchronized Categories --}}
                <li class="nav-item w-100">
                    <a id="main-menu-sync-categories" href="{{ route('vendor.categories.synchronized') }}"
                       class="nav-link text-gray pl-4 submenu-link">Категории</a>
                </li>

                {{--Jobs queue --}}
                <li class="nav-item w-100">
                    <a id="main-menu-sync-jobs" href="{{ route('vendor.synchronization.index') }}"
                       class="nav-link text-gray pl-4 submenu-link">Задания</a>
                </li>

            </ul>

        </li>
        @endif

        {{-- Users--}}
        @if(Gate::allows('users-edit'))
        <li class="nav-item w-100">

            <a class="nav-link text-gray position-relative pl-0 pr-4" data-toggle="collapse" aria-expanded="false"
               data-target="#main-menu-users" aria-controls="main-menu-users">Пользователи</a>

            <ul id="main-menu-users" class="nav collapse">

                {{-- Admins --}}
                @if(Gate::allows('admins-edit'))
                <li class="nav-item w-100">
                    <a id="main-menu-users-admins" href="{{ route('admin.users.administrators') }}"
                       class="nav-link text-gray pl-4 submenu-link">Сотрудники</a>
                </li>
                @endif

                {{-- Customers --}}
                <li class="nav-item w-100">
                    <a id="main-menu-users-customers" href="{{ route('admin.users.customers') }}"
                       class="nav-link text-gray pl-4 submenu-link">Покупатели</a>
                </li>

            </ul>

        </li>
        @endif

        {{-- Settings--}}
        @if(Gate::allows('settings-edit'))
        <li class="nav-item w-100">

            <a class="nav-link text-gray position-relative pl-0" data-toggle="collapse" aria-expanded="false"
               data-target="#main-menu-settings" aria-controls="main-menu-settings">Настройки</a>

            <ul id="main-menu-settings" class="nav collapse">

                {{-- SEO --}}
                <li class="nav-item w-100">
                    <a id="main-menu-settings-seo" href="{{ route('admin.settings.seo.edit') }}"
                       class="nav-link text-gray pl-4 submenu-link">Seo</a>
                </li>

                {{-- Shop --}}
                <li class="nav-item w-100">
                    <a id="main-menu-settings-shop" href="{{ route('admin.settings.shop.edit') }}"
                       class="nav-link text-gray pl-4 submenu-link">Магазин</a>
                </li>

                {{-- Vendor --}}
                <li class="nav-item w-100">
                    <a id="main-menu-settings-vendors" href="{{ route('admin.settings.vendor.edit') }}"
                       class="nav-link text-gray pl-4 submenu-link">Поставщики</a>
                </li>

            </ul>

        </li>
        @endif

        {{-- Content--}}
        @if(Gate::allows('content-edit'))
        <li class="nav-item w-100">

            <a class="nav-link text-gray position-relative pl-0" data-toggle="collapse" aria-expanded="false"
               data-target="#main-menu-content" aria-controls="main-menu-content">Содержимое</a>

            <ul id="main-menu-content" class="nav collapse">

                {{-- Common --}}
                <li class="nav-item w-100">
                    <a id="main-menu-content-common" href="{{ route('admin.content.common.edit') }}"
                       class="nav-link text-gray pl-4 submenu-link">Общее</a>
                </li>

                {{-- Main --}}
                <li class="nav-item w-100">
                    <a id="main-menu-content-main" href="{{ route('admin.content.main.edit') }}"
                       class="nav-link text-gray pl-4 submenu-link">Главная</a>
                </li>

            </ul>

        </li>
        @endif

    </ul>

</div>
