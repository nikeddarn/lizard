<?php
/**
 * Brain vendor provider.
 */

namespace App\Support\Vendors\Providers;


use App\Contracts\Vendor\VendorInterface;
use App\Exceptions\Vendor\AccessDeniedException;
use Closure;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;
use GuzzleHttp\Client;

class BrainProvider extends VendorProvider
{
    const BASE_URI = 'http://api.brain.com.ua';

    const LOGIN = 'sol_dim@mail.ru';
    const PASSWORD = '4691000';

    const MODIFIED_PRODUCTS_LIMIT = 10000;

    const SESSION_ID_CACHE_KEY = VendorInterface::BRAIN . '_session_id';
    const SESSION_ID_FAIL_MESSAGE = '';

    /**
     * @var string|null
     */
    private $sessionId = null;

    /**
     * Create Guzzle client.
     *
     * @return Client
     */
    protected function createClient()
    {
        return new Client([
            'base_uri' => self::BASE_URI,
        ]);
    }

    /**
     * Get vendor single query result.
     *
     * @param string $method
     * @param Closure $requestUriGenerator
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    protected function getSingleResponse(string $method, Closure $requestUriGenerator, array $options = [])
    {
        try {
            // get session id
            $sessionId = $this->getSessionId();

            // get request uri
            $requestUri = $requestUriGenerator($sessionId);

            $result = $this->executeSingleQuery($method, $requestUri, $options);

            // store valid session id
            if (!Cache::has(self::SESSION_ID_CACHE_KEY)) {
                Cache::put(self::SESSION_ID_CACHE_KEY, $this->sessionId, config('admin.vendor_session_id_ttl.' . VendorInterface::BRAIN));
            }

            return $result;

        } catch (AccessDeniedException $exception) {
            // clear session id
            Cache::forget(self::SESSION_ID_CACHE_KEY);

            // retry get response with new session id
            return $this->{__FUNCTION__}($method, $requestUriGenerator, $options);

        } catch (GuzzleException|Throwable|Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Get vendor pool query results.
     *
     * @param Closure $requestsUriGenerator
     * @return array
     * @throws Exception
     */
    protected function getPoolResponses(Closure $requestsUriGenerator): array
    {
        try {
            // get session id
            $sessionId = $this->getSessionId();

            // prepare requests
            $requests = $requestsUriGenerator($sessionId);

            // execute queries
            $results = $this->executePoolQueries($requests);

            // store valid session id
            if (!Cache::has(self::SESSION_ID_CACHE_KEY)) {
                Cache::put(self::SESSION_ID_CACHE_KEY, $this->sessionId, config('admin.vendor_session_id_ttl.' . VendorInterface::BRAIN));
            }

            return $results;

        } catch (AccessDeniedException $exception) {
            // clear session id
            Cache::forget(self::SESSION_ID_CACHE_KEY);

            // retry get response with new session id
            return $this->{__FUNCTION__}($requestsUriGenerator);

        } catch (GuzzleException|Throwable|Exception $exception) {
            throw new  Exception($exception->getMessage());
        }
    }

    /**
     * @param string $method
     * @param string $requestUri
     * @param array $options
     * @return mixed
     * @throws GuzzleException
     * @throws AccessDeniedException
     * @throws Exception
     */
    private function executeSingleQuery(string $method, string $requestUri, array $options = [])
    {
        $responseData = json_decode($this->client->request($method, $requestUri, $options)->getBody()->getContents());

        if ($responseData->status == 0) {
            // access denied. refresh session id or throw exception
            if (Cache::has(self::SESSION_ID_CACHE_KEY)) {
                // reattempt requests with fresh session id
                throw new AccessDeniedException($responseData->error_message);
            } else {
                // get error message
                $message = $responseData->error_message;
                // log message
                Log::alert($message);
                // throw exception
                throw new Exception($message);
            }
        }

        return $responseData->result;
    }/** @noinspection PhpDocRedundantThrowsInspection */

    /**
     * Execute pool queries.
     *
     * @param array $requests
     * @return array
     * @throws AccessDeniedException
     * @throws Exception
     * @noinspection PhpDocRedundantThrowsInspection
     */
    private function executePoolQueries(array $requests)
    {
        $results = [];

        $pool = new Pool($this->client, $requests, [
            'concurrency' => self::CONCURRENCY_REQUESTS_COUNT,
            'fulfilled' => function ($response, $index) use (&$results) {
                $responseData = json_decode($response->getBody()->getContents());

                if ($responseData->status == 0) {
                    // access denied. refresh session id or throw exception
                    if (Cache::has(self::SESSION_ID_CACHE_KEY)) {
                        // reattempt requests with fresh session id
                        throw new AccessDeniedException($responseData->error_message);
                    } else {
                        // get error message
                        $message = $responseData->error_message;
                        // log message
                        Log::alert($message);
                        // throw exception
                        throw new Exception($message);
                    }
                } else {
                    // get successful result
                    $results[$index] = $responseData->result;
                }
            },
            'rejected' => function ($reason, $index) {
                throw new Exception($index . ': ' . $reason);
            },
        ]);

        // initiate the transfers and create a promise
        $promise = $pool->promise();

        // force the pool of requests to complete
        $promise->wait();

        return $results;
    }

    /**
     * Get session ID.
     *
     * @return string
     * @throws Exception
     * @throws GuzzleException
     */
    protected function getSessionId(): string
    {
        if (Cache::has(self::SESSION_ID_CACHE_KEY)) {
            // get sess id from cache
            return Cache::get(self::SESSION_ID_CACHE_KEY);
        } else {
            // get sess id from vendor
            $this->sessionId = $this->executeSingleQuery('POST', '/auth', [
                'form_params' => [
                    'login' => self::LOGIN,
                    'password' => md5(self::PASSWORD),
                ]
            ]);

            return $this->sessionId;
        }
    }
}
