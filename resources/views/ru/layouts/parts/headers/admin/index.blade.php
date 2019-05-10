<header id="admin-header" class="container-fluid">

    <div class="row py-2">

        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                @include('layouts.parts.headers.admin.parts.hamburger')
                @include('layouts.parts.headers.admin.parts.logo')
            </div>
            <div class="col-auto">

                <span>Текущий курс:&nbsp;<strong>{{ $headerData['course'] }}</strong>&nbsp;грн</span>

                <span class="ml-5">Баланс смс шлюза:&nbsp;<strong class="{{ $headerData['smsSenderBalance']['low_balance'] ? 'text-danger' : '' }}">{{ $headerData['smsSenderBalance']['value'] }}</strong>&nbsp;грн</span>

                <span class="ml-5">Использование дисков: основной - <strong>{{ $headerData['disksUsage']['main'] }}</strong> облачный - <strong>{{ $headerData['disksUsage']['cloud'] }}</strong></span>

            </div>
            <div>
                @include('layouts.parts.headers.admin.parts.menu')
            </div>
        </div>

    </div>

    @include('layouts.parts.admin.menu.main_menu_modal')

</header>
