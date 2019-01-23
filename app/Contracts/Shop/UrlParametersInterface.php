<?php
/**
 * Query string parameters.
 */

namespace App\Contracts\Shop;


interface UrlParametersInterface
{
    const LOCALE = 'locale';

    const SORT_PRODUCTS = 'sort';

    const SHOW_PRODUCTS = 'show';

    const FILTERED_PRODUCTS = 'filter';

    const PRODUCTS_PAGE = 'page';
}
