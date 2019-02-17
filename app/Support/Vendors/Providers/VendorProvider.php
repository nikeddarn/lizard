<?php
/**
 * Common vendor provider.
 */

namespace App\Support\Vendors\Providers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

class VendorProvider
{
    /**
     * @var int
     */
    const CONCURRENCY_REQUESTS_COUNT = 1;

    /**
     * @var Client Guzzle client
     */
    protected $client;

    /**
     * @var string Current locale
     */
    protected $locale;

    /**
     * VendorProvider constructor.
     */
    public function __construct()
    {
        $this->locale = app()->getLocale();

        $this->client = static::createClient();
    }

    /**
     * Query request by GET method.
     *
     * @param string $method
     * @param string $requestUri
     * @param array $options
     * @return string
     * @throws Exception
     */
    public function getSingleQueryResponseContent(string $method, string $requestUri, array $options = [])
    {
        try {
            // get response
            $responseContent = $this->client->request($method, $requestUri, $options)->getBody()->getContents();

        } catch (GuzzleException|Throwable|Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        return $responseContent;
    }

    /**
     * Query pool of requests by GET method. Return successful results.
     *
     * @param array $paths
     * @return array
     */
    public function getPoolQueriesResponseContent(array $paths)
    {
        $requests = [];

        // create requests
        foreach ($paths as $uri){
            $requests[$uri] = new Request('GET', $uri);
        }

        $results = [];

        $pool = new Pool($this->client, $requests, [
            'concurrency' => self::CONCURRENCY_REQUESTS_COUNT,
            'fulfilled' => function ($response, $index) use (&$results) {
                // set successful result
                $results[$index] = $response->getBody()->getContents();
            },
            'rejected' => function ($reason, $index) {

            },
        ]);

        // initiate the transfers and create a promise
        $promise = $pool->promise();

        // force the pool of requests to complete
        $promise->wait();

        return $results;
    }

    /**
     * Create Guzzle client.
     *
     * @return Client
     */
    protected function createClient()
    {
        return new Client();
    }
}
