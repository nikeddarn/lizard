<div class="container">
    <div class="row align-items-center">
        <div class="col-12  d-md-none mb-2">
            @include('layouts.parts.headers.common.middle.parts.logo')
        </div>
    </div>
</div>

<div id="header-middle">


    <div class="container">

        <div class="row align-items-center my-2">

            <div class="col-12 col-md-3 d-none d-md-block">
                @include('layouts.parts.headers.common.middle.parts.logo')
            </div>

            <div class="col-2 d-flex d-md-none text-center">
                @include('layouts.parts.headers.common.middle.parts.modal_toggle')
            </div>

            <div class="col-10 col-md-6">
                @include('layouts.parts.headers.common.middle.parts.search')
            </div>

            <div class="col-md-3 d-none d-md-block">
                @include('layouts.parts.headers.common.middle.parts.actions')
            </div>

        </div>
    </div>
</div>