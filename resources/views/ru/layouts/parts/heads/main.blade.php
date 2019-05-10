<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-41789543-2"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-41789543-2');
</script>


{{-- Bootstrap meta--}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

{{-- link favicon --}}
<link rel="shortcut icon" href="{{{ url('/images/common/icon.ico') }}}">

{{-- Seo alternate locales links--}}
@if(empty($noindexPage) && !empty($alternateLocalesLinks))
    @foreach($alternateLocalesLinks as $alternateLanguageLink)
        {!! $alternateLanguageLink !!}
    @endforeach
@endif

{{-- Seo pagination links--}}
@if(empty($noindexPage) && !empty($paginationLinks))
    @foreach($paginationLinks as $paginationLink)
        {!! $paginationLink !!}
    @endforeach
@endif

{{-- Robots meta --}}
@if(!empty($noindexPage))
    <meta name="robots" content="noindex,nofollow">
@endif

{{-- Canonical meta --}}
@if(empty($noindexPage) && !empty($metaCanonical))
    <link rel="canonical" href="{{ $metaCanonical }}">
@endif

@if(!empty($pageTitle))
    <title>{{ $pageTitle }}</title>
@endif

@if(!empty($pageDescription))
    <meta name="description" content="{{ $pageDescription }}">
@endif

@if(!empty($pageKeywords))
    <meta name="keywords" content="{{ $pageKeywords }}">
@endif

{{-- Laravel token--}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Yield custom page's meta section if exists--}}
@yield('meta')

{{-- Yield custom page's styles section if exists--}}
@yield('styles')

{{-- Application css file --}}
<link href="{{ url('/css/app.css') }}" rel="stylesheet">

{{-- Font-awesome--}}
<link href="{{ url('/css/font-awesome.min.css') }}" rel="stylesheet">


<!-- Custom css -->
<link href="{{ mix('css/lizard.css') }}" rel="stylesheet">


{{-- Application js file --}}
<script type="text/javascript" src="{{ url('/js/app.js') }}"></script>


<script type="text/javascript" src="{{ mix('js/lizard.js') }}"></script>

<script type="text/javascript" src="{{ url('/js/isotope.min.js') }}"></script>

<script src="{{ url('/js/feather.min.js') }}"></script>

<script>

    // append csrf to ajax headers
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        // replace feather icons with svg
        feather.replace();

        // -------------------------------- Activate mega menu -------------------------------------

        let headCatalogDropdown = ('#head-catalog-dropdown');
        let leftMenuCategories = $('#left-menu-categories');

        // disable open mega menu onclick events
        if (!('ontouchstart' in window)) {
            $(headCatalogDropdown).find('.dropdown-toggle').click(function (e) {
                e.stopImmediatePropagation();
            });
        }

        // activate and deactivate mega menu on hover mega menu dropdown or left menu categories
        $(headCatalogDropdown).hover(function (event) {
            event.stopPropagation();
            event.stopImmediatePropagation();

            activateMegaMenu(headCatalogDropdown);
        }, function () {
            deactivateMegaMenu(headCatalogDropdown);
        });

        function activateMegaMenu(headCatalogDropdown) {
            $(headCatalogDropdown).addClass('show').find('.dropdown-menu').addClass('show').css({
                'opacity': 0
            }).stop(true).animate({
                opacity: 1
            }, 300, null);
        }

        function deactivateMegaMenu(headCatalogDropdown) {
            $(headCatalogDropdown).removeClass('show');
            $(headCatalogDropdown).find('.dropdown-menu').removeClass('show');
        }

        // ----------------------------------- Mega Menu Content ------------------------------------

        $('.main-menu-category').hover(function (event) {
            event.stopPropagation();
            event.stopImmediatePropagation();


            let oldActiveSubcategory = $('.main-menu-subcategory.show');

            let newCategoryId = $(this).data('category-id');
            let newActiveSubcategory = $('#mega-menu-subcategories').find('#mega-menu-children-' + newCategoryId);

            // highlight current category
            $('.main-menu-category.show').removeClass('show');
            $(this).addClass('show');

            let headCatalogDropdown = ('#head-catalog-dropdown');

            if ($(headCatalogDropdown).hasClass('show')) {
                changeShowingSubcategory(oldActiveSubcategory, newActiveSubcategory);
            } else {
                activateMegaMenu(headCatalogDropdown);
            }
        }, function (event) {
            if (!$(event.relatedTarget).closest('#head-catalog-dropdown').length) {
                let headCatalogDropdown = ('#head-catalog-dropdown');
                deactivateMegaMenu(headCatalogDropdown);
            }
        });

        function changeShowingSubcategory(oldActiveSubcategory, newActiveSubcategory) {
            if (oldActiveSubcategory.is(newActiveSubcategory)) {
                return;
            }

            if (oldActiveSubcategory) {
                $(oldActiveSubcategory).stop(true).animate({
                    opacity: 0
                }, 100, null, function () {
                    $(oldActiveSubcategory).removeClass('d-block show').addClass('d-none');
                    activateSubcategory(newActiveSubcategory);
                });
            } else {
                activateSubcategory(newActiveSubcategory);
            }
        }

        function activateSubcategory(subcategory) {
            let currentGrid = $(subcategory).find('.grid');

            $(subcategory).css('opacity', 0).removeClass('d-none').addClass('d-block show');

            // layout complete handler
            $(currentGrid).off('layoutComplete').on('layoutComplete', function () {
                $(subcategory).stop(true).animate({
                    opacity: 1
                }, 100);
            });

            $(currentGrid).masonry({
                transitionDuration: 0
            });
        }

    });

</script>

{{-- Yield custom page's scripts if exists--}}
@yield('scripts')
