<?php
/**
 * SEO canonical Link generator.
 */

namespace App\Support\Seo\Canonical;


use App\Contracts\Shop\UrlParametersInterface;
use Illuminate\Http\Request;

class CanonicalLinkGenerator
{
    /**
     * @var array
     */
    const CANONICAL_PARAMETERS = [UrlParametersInterface::PRODUCTS_PAGE];
    /**
     * @var Request
     */
    private $request;

    /**
     * CanonicalLinkGenerator constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create canonical url.
     *
     * @return string|null
     */
    public function createCanonicalLinkUrl()
    {
        $currentQueryParameters = $this->request->query();

            $currentCanonicalQueryParameters = array_intersect_key($currentQueryParameters, array_flip(self::CANONICAL_PARAMETERS));

        if (count($currentQueryParameters) > count($currentCanonicalQueryParameters)) {

            $currentRouteParameters = $this->request->route()->parameters();

            $currentRouteName = $this->request->route()->getName();

            return route($currentRouteName, $currentRouteParameters) . $this->createQueryString($currentCanonicalQueryParameters);
        } else {
            return null;
        }
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
