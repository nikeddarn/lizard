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

class BrainVendorProvider implements VendorProviderInterface
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
     * Get vendor categories tree.
     *
     * @return Collection
     * @throws Exception
     */
    public function getCategories(): Collection
    {
        $sessionId = $this->getSessionId();

        $uri = "categories/$sessionId?lang=$this->locale";

        $response = json_decode($this->client->request('GET', $uri)->getBody()->getContents());

        if ($response->status === 0){
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        $categories = $response->result;

        return $this->buildTree($categories);
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

        if ($response->status === 0){
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
     * @param int $page
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getCategoryProducts(int $categoryId, int $page): LengthAwarePaginator
    {
        $sessionId = $this->getSessionId();

        $productsPerPage = config('admin.vendor_products_per_page');

        $offset = ($page - 1) * $productsPerPage;
        $uri = "products/$categoryId/$sessionId?limit=$productsPerPage&offset=$offset";

        $response = json_decode($this->client->request('GET', $uri)->getBody()->getContents());

        if ($response->status === 0){
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        $productsData = $response->result;

        $products = collect($productsData->list)->each(function ($product){
            $product->id = $product->productID;
        });

        $productsTotal = $productsData->count;


        return new LengthAwarePaginator($products, $productsTotal, $productsPerPage, $page, [
            'path' => url()->current(),
        ]);
    }

    /**
     * Get products data.
     *
     * @param array $vendorProductsIds
     * @param string $locale
     * @return Collection
     * @throws Exception
     */
    public function getProductsData(array $vendorProductsIds, string $locale): Collection
    {
        $sessionId = $this->getSessionId();

        $uri = "products/content/$sessionId?lang=$locale";

        $response = json_decode($this->client->request('POST', $uri, [
            'form_params' => [
                'lang' => $locale,
                'productIDs' => implode(',', $vendorProductsIds),
            ],
        ])->getBody()->getContents());

        if ($response->status === 0){
            throw new Exception('Can not get data from vendor. Try again later.');
        }

        return collect($response->result->list);
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

    /**
     * Build tree from categories.
     *
     * @param array $elements
     * @param int $parentId
     * @return Collection
     */
    private function buildTree(array &$elements, $parentId = 1): Collection
    {
        $branch = collect();

        foreach ($elements as $element) {
            if ($element->parentID == $parentId && $element->realcat == 0) {
                $children = $this->buildTree($elements, $element->categoryID);
                if ($children) {
                    $element->children = $children;
                }
                $element->id = $element->categoryID;
                $branch->push($element);
            }
        }

        return $branch;
    }
}