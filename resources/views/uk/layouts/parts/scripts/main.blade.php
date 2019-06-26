{{-- Application js file --}}
<script ref="preload" type="text/javascript" src="{{ url('/js/app.js') }}"></script>


<script ref="preload" type="text/javascript" src="{{ mix('js/lizard.js') }}"></script>

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
