<nav class="nav">

    @if(!empty($shopContacts['phones']))

        <span class="nav-link py-1 px-0 d-none d-sm-inline">
            <i class="svg-icon" data-feather="phone"></i>
        </span>

        <a class="nav-link px-2 py-1 text-gray d-none d-sm-inline"
           href="tel:{{ $shopContacts['phones'][0] }}">{{ $shopContacts['phones'][0] }}</a>

        @if(isset($shopContacts['phones'][1]))
            <a class="nav-link px-2 py-1 text-gray d-none d-md-inline"
               href="tel:{{ $shopContacts['phones'][1] }}">{{ $shopContacts['phones'][1] }}</a>
        @endif

        @if(isset($shopContacts['phones'][2]))
            <a class="nav-link px-2 py-1 text-gray d-none d-lg-inline"
               href="tel:{{ $shopContacts['phones'][2] }}">{{ $shopContacts['phones'][2] }}</a>
        @endif

    @endif

</nav>
