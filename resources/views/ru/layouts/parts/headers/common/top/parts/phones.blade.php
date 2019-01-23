<nav class="nav">

    @if(!empty($shopContacts['phones']))

        <span class="nav-link py-1 px-0">
            <i class="svg-icon" data-feather="phone"></i>
        </span>

        @for($i=0; $i<2; $i++)
            @if(isset($shopContacts['phones'][$i]))
                <a class="nav-link px-2 py-1 text-gray"
                   href="tel:{{ $shopContacts['phones'][$i] }}">{{ $shopContacts['phones'][$i] }}</a>
            @endif
        @endfor

    @endif

</nav>
