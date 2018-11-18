<?php
/**
 * Brain vendor provider.
 */

namespace App\Support\Vendors\Providers;


use App\Contracts\Vendor\VendorProviderInterface;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BrainVendorProvider
{
    const LOGIN = 'sol_dim@mail.ru';
    const PASSWORD = '46910000';
    const BASE_URI = 'http://api.brain.com.ua';

    /**
     * @var string Current locale
     */
    public $locale;

    /**
     * @var Client Guzzle client
     */
    public $client;

    /**
     * BrainVendorProvider constructor.
     */
    public function __construct()
    {
        $this->locale = app()->getLocale();
        $this->client = $this->getClient();
    }

    /**
     * Get vendor categories.
     *
     * @return array
     * @throws Exception
     */
    public function getCategories(): array
    {
        $sessionId = $this->getSessionId();

        $uri = "categories/$sessionId?lang=$this->locale";

        $response = json_decode($this->client->request('GET', $uri)->getBody()->getContents());

        if ($response->status === 0) {
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        return $response->result;
    }

    /**
     * Get vendor category by id.
     *
     * @param int $categoryId
     * @param string|null $locale
     * @return object
     * @throws Exception
     */
    public function getCategory(int $categoryId, string $locale = null): object
    {
        $categoryLocale = $locale ?? $this->locale;

        $sessionId = $this->getSessionId();

        $uri = "categories/$sessionId?lang=$categoryLocale";

        $response = json_decode($this->client->request('GET', $uri)->getBody()->getContents());

        if ($response->status === 0) {
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        $categories = collect($response->result)->keyBy('categoryID');

        $category = $categories->get($categoryId);

        $category->id = $category->categoryID;

        return $category;
    }

    /**
     * Get products of category.
     *
     * @param int $categoryId
     * @param int $productsPerPage
     * @param int $offset
     * @return object
     * @throws Exception
     */
    public function getCategoryProducts(int $categoryId, int $productsPerPage, int $offset): object
    {
        $sessionId = $this->getSessionId();

        $uri = "products/$categoryId/$sessionId?limit=$productsPerPage&offset=$offset";

        $response = json_decode($this->client->request('GET', $uri)->getBody()->getContents());

        if ($response->status === 0) {
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        return $response->result;
    }

    /**
     * Get product data.
     *
     * @param int $productId
     * @param string $locale
     * @return object
     * @throws Exception
     */
    public function getProductData(int $productId, string $locale)
    {
        $sessionId = $this->getSessionId();

        $uri = "product/$productId/$sessionId?lang=$locale";

        $response = json_decode($this->client->request('GET', $uri)->getBody()->getContents());

        if ($response->status === 0) {
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        return $response->result;
    }

    /**
     * Get product content data.
     *
     * @param int $productId
     * @param string $locale
     * @return object
     * @throws Exception
     */
    public function getProductContentData(int $productId, string $locale): object
    {
        $sessionId = $this->getSessionId();

        $uri = "products/content/$sessionId?lang=$locale";

        $response = json_decode($this->client->request('POST', $uri, [
            'form_params' => [
                'lang' => $locale,
                'productIDs' => $productId,
            ],
        ])->getBody()->getContents());

        if ($response->status === 0) {
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        return $response->result->list[0];
    }

//    public function getNewProducts(Carbon $fromTime)
//    {
//        $sessionId = $this->getSessionId();
//
//        $uri = "modified_products/new/$sessionId?modified_time=2016-04-17+07:00:00][&limit=1000][&offset=1000]";
//
//        $productsData = json_decode($this->client->request('GET', $uri)->getBody()->getContents())->result;
//    }


    /**
     * Get vendors' brands.
     *
     * @return array
     * @throws Exception
     */
    public function getBrands(): array
    {
        $sessionId = $this->getSessionId();

        $uri = "vendors/$sessionId";

        $response = json_decode($this->client->request('GET', $uri)->getBody()->getContents());

        if ($response->status === 0) {
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        return $response->result;
    }

    /**
     * Get stocks.
     *
     * @return mixed
     * @throws Exception
     */
    public function getStocks()
    {
        $sessionId = $this->getSessionId();

        $uri = "stocks/$sessionId";

        $response = json_decode($this->client->request('GET', $uri)->getBody()->getContents());

        if ($response->status === 0) {
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        return $response->result;
    }

    /**
     * Get USD courses.
     *
     * @return array
     * @throws Exception
     */
    public function getUsdCourses(): array
    {
        $sessionId = $this->getSessionId();

        $uri = "currencies/$sessionId";

        $response = json_decode($this->client->request('GET', $uri)->getBody()->getContents());

        if ($response->status === 0) {
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        return $response->result;
    }


    /**
     * Get Guzzle client.
     *
     * @return Client
     */
    private function getClient()
    {
        return new Client([
            'base_uri' => self::BASE_URI,
        ]);
    }

    /**
     * Get session ID.
     *
     * @return string
     */
    private function getSessionId(): string
    {
        $options = ['form_params' => [
            'login' => self::LOGIN,
            'password' => md5(self::PASSWORD),
        ]];

        return json_decode($this->client->request('POST', '/auth', $options)->getBody()->getContents())->result;
    }
}