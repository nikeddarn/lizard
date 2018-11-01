<div id="headerTopPanel">
    <div class="container">
        <div class="row">

            <div class="col-6 col-md-auto col-lg-10">
                <div class="row">

                    <div class="col-12 col-md-auto">
                        @include('layouts.parts.headers.common.top.parts.language')
                    </div>

                    <div class="col-md-auto d-none d-md-block">
                        @include('layouts.parts.headers.common.top.parts.menu')
                    </div>

                </div>
            </div>


            <div class="col-6 col-md-auto col-lg-2 text-right">
                @if(auth('web')->check())
                    @include('layouts.parts.headers.common.top.parts.user')
                @else
                    @include('layouts.parts.headers.common.top.parts.login')
                @endif
            </div>

        </div>
    </div>
</div>