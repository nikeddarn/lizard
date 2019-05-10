<div id="header-top">
    <div class="container">
        <div class="row">

            <div class="col-auto px-2 pl-3 px-sm-3">
                @include('layouts.parts.headers.common.top.parts.phones')
            </div>

{{--            <div class="col-auto mr-auto">--}}
{{--                @include('layouts.parts.headers.common.top.parts.socials')--}}
{{--            </div>--}}

            <div class="col-auto px-2 px-sm-3 ml-auto">
                @include('layouts.parts.headers.common.top.parts.language')
            </div>

            <div class="col-auto px-2 pr-3 px-sm-3">
                @if(auth('web')->check())
                    @include('layouts.parts.headers.common.top.parts.user')
                @else
                    @include('layouts.parts.headers.common.top.parts.login')
                @endif
            </div>

        </div>
    </div>
</div>
