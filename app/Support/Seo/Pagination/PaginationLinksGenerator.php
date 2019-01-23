<?php
/**
 * Seo pagination links generator.
 */

namespace App\Support\Seo\Pagination;


use App\Contracts\Shop\UrlParametersInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginationLinksGenerator
{
    /**
     * Create seo pagination links
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    public function createSeoLinks(LengthAwarePaginator $paginator):array
    {
        $links = [];

        $currentUrl = request()->url();

        $queryParameters = request()->query();

        $currentPage = $paginator->currentPage();

        if ($paginator->previousPageUrl()){

            if ($currentPage > 2){
            // add 'page' parameter in query string
                $queryParameters[UrlParametersInterface::PRODUCTS_PAGE] = $currentPage - 1;
            }else{
                // remove 'page' parameter from query string
                unset($queryParameters[UrlParametersInterface::PRODUCTS_PAGE]);
            }

            $prevPageUrl = $currentUrl . $this->createQueryString($queryParameters);

            $links[] = '<link rel="prev" href="' . $prevPageUrl . '">';
        }

        if ($paginator->nextPageUrl()){
            // add 'page' parameter in query string
            $queryParameters[UrlParametersInterface::PRODUCTS_PAGE] = $currentPage + 1;

            $nextPageUrl = $currentUrl . $this->createQueryString($queryParameters);

            $links[] = '<link rel="next" href="' . $nextPageUrl . '">';
        }

        return $links;
    }

    /**
     * Create query string.
     *
     * @param array $parameters
     * @return string
     */
    protected function createQueryString(array $parameters)
    {
        return $parameters ? '?' . urldecode(http_build_query($parameters)) : '';
    }
}
