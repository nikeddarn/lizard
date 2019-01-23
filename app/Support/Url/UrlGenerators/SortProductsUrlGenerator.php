<?php
/**
 * Generate possible locales urls.
 */

namespace App\Support\Url\UrlGenerators;


class SortProductsUrlGenerator extends UrlGenerator
{

    public function __construct()
    {
        $this->queryStringParameterName = 'sort';

        $this->canonicalQueryStringParameterValue = config('shop.products_sort.canonical_sort_method');

        $this->availableQueryStringParameterValues = config('shop.products_sort.possible_sort_methods');

        $this->resetPage = true;
    }
}
