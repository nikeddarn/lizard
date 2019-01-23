<?php
/**
 * SEO alternate links generator.
 */

namespace App\Support\Seo\Locale;


use App\Contracts\Shop\UrlParametersInterface;
use Illuminate\Http\Request;

class AlternateLinksGenerator
{
    /**
     * @var Request
     */
    private $request;

    /**
     * AlternateLinksGenerator constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create seo alternate languages links.
     *
     * @return array
     */
    public function createAlternateLinks()
    {
        $links = [];


        // locale parameter is not allowed for current route
        if (!in_array(UrlParametersInterface::LOCALE, $this->request->route()->parameterNames())){
            return $links;
        }

        // get canonical locale
        $canonicalLocale = config('app.canonical_locale');

        // get current route parameters
        $currentRouteParameters = $this->request->route()->parameters();

        // get route name
        $routeName = $this->request->route()->getName();

        // get query string
        $queryStringParameters = $this->request->query();

        foreach(config('app.available_locales') as $availableLocale){
            if($availableLocale === $canonicalLocale){
                $localeRouteParameters = array_merge($currentRouteParameters, ['locale' => null]);
            }else{
                $localeRouteParameters = array_merge($currentRouteParameters, ['locale' => $availableLocale]);
            }

            $localeUri = route($routeName, $localeRouteParameters) . $this->createQueryString($queryStringParameters);

            $links[] = '<link rel="alternate" hreflang="' . $availableLocale . '" href="' . $localeUri . '"/>';
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
