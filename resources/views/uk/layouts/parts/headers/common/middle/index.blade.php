<div id="header-middle">
    <div class="container">
        <div class="row py-2">

            <div class="col-12 d-flex align-items-center justify-content-between">

                <div class="d-inline-flex align-items-center">
                    @include('layouts.parts.headers.common.middle.parts.hamburger')
                    @include('layouts.parts.headers.common.middle.parts.logo')
                    @include('layouts.parts.headers.common.middle.parts.menu')
                </div>

                <div class="d-inline-flex align-items-center">

                    <div id="header-search" class="d-none d-md-inline-block form-search">
                        @include('layouts.parts.headers.common.middle.parts.search')
                    </div>

                    @include('layouts.parts.headers.common.middle.parts.actions')
                </div>

            </div>

        </div>
    </div>
</div>
