<?php
/**
 * Generate possible locales urls.
 */

namespace App\Support\Url\UrlGenerators;


use App\Contracts\Shop\ShowProductsInterface;

class ShowProductsUrlGenerator extends UrlGenerator
{
    /**
     * @var array
     */
    private $iconClasses = [
        ShowProductsInterface::GRID => 'grid',
        ShowProductsInterface::LIST => 'list',
    ];

    public function __construct()
    {
        $this->queryStringParameterName = 'show';

        $this->canonicalQueryStringParameterValue = config('shop.products_show.canonical_show_method');

        $this->availableQueryStringParameterValues = config('shop.products_show.possible_show_methods');
    }

    /**
     * Get icon class for url.
     *
     * @param string $parameterValue
     * @return string
     */
    protected function getUrlIconClass(string $parameterValue):string
    {
        return $this->iconClasses[$parameterValue];
    }
}
