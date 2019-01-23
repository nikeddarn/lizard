<?php
/**
 * Generate urls by get parameters.
 */

namespace App\Support\Url\UrlGenerators;


abstract class UrlGenerator
{
    /**
     * @var string
     */
    const PAGE_QUERY_PARAMETER_NAME = 'page';

    /**
     * @var bool
     */
    protected $resetPage = false;

    /**
     * @var string
     */
    protected $queryStringParameterName;

    /**
     * @var string
     */
    protected $canonicalQueryStringParameterValue;

    /**
     * @var array
     */
    protected $availableQueryStringParameterValues;

    /**
     * Get query string parameter name.
     *
     * @return string
     */
    public function getQueryStringParameterName():string
    {
        return $this->queryStringParameterName;
    }

    /**
     * Create available links data.
     *
     * @return array
     */
    public function createAvailableLinksData()
    {
        $possibleLocalesLinksData = [];

        $currentParameterValue = $this->getCurrentQueryStringParameterValue();

        foreach ($this->availableQueryStringParameterValues as $parameterValue) {

            // create url data
            $urlData = [];

            $urlData['url'] = $this->createUrl($parameterValue);
            $urlData['class'] = $parameterValue === $currentParameterValue ? 'disabled' : '';

            if (method_exists($this, 'getUrlIconClass')){
            $urlData['icon'] = $this->getUrlIconClass($parameterValue);
            }

            // collect url data
            $possibleLocalesLinksData[$parameterValue] = $urlData;
        }

        return $possibleLocalesLinksData;
    }

    /**
     * Create url for given query string parameter value.
     *
     * @param string $parameterValue
     * @return string
     */
    public function createUrl(string $parameterValue): string
    {
        if ($parameterValue === $this->canonicalQueryStringParameterValue) {
            return $this->createCanonicalUrl();
        } else {
            return $this->createNotCanonicalUrl($parameterValue);
        }
    }

    /**
     * Create canonical locale url.
     *
     * @return string
     */
    public function createCanonicalUrl(): string
    {
        // current query string params
        $queryStringParameters = request()->query();

        // remove given parameter from query
        unset($queryStringParameters[$this->queryStringParameterName]);

        // reset page to start page
        if ($this->resetPage){
            unset($queryStringParameters[self::PAGE_QUERY_PARAMETER_NAME]);
        }

        // create canonical url
        return $this->makeUrl($queryStringParameters);
    }

    /**
     * Create not canonical locale url.
     *
     * @param string $parameterValue
     * @return string
     */
    public function createNotCanonicalUrl(string $parameterValue): string
    {
        // current query string params
        $queryStringParameters = request()->query();

        // add parameter to query string
        $queryStringParameters[$this->queryStringParameterName] = $parameterValue;

        // reset page to start page
        if ($this->resetPage){
            unset($queryStringParameters[self::PAGE_QUERY_PARAMETER_NAME]);
        }

        return $this->makeUrl($queryStringParameters);
    }

    /**
     * Get current query string parameter value.
     *
     * @return string
     */
    public function getCurrentQueryStringParameterValue(): string
    {
        if (request()->has($this->queryStringParameterName)) {
            return request()->get($this->queryStringParameterName);
        } else {
            return $this->canonicalQueryStringParameterValue;
        }
    }

    /**
     * Create full url.
     *
     * @param array $queryStringParameters
     * @return string
     */
    private function makeUrl(array $queryStringParameters): string
    {
        // get request uri
        $requestUri = url()->current();

        // create query string
        $queryString = http_build_query($queryStringParameters);

        // create not canonical url
        return $requestUri . ($queryString ? '?' . $queryString : '');
    }
}
