<?php
/**
 * Brain catalog provider.
 */

namespace App\Support\Vendors\Providers;


use Exception;
use GuzzleHttp\Psr7\Request;
use stdClass;

class BrainCatalogProvider extends BrainProvider
{

    /**
     * Get vendor categories.
     *
     * @return array
     * @throws Exception
     */
    public function getCategories(): array
    {
        return $this->getSingleResponse('GET', function ($sessionId){
            return "categories/$sessionId?lang=$this->locale";
        });
    }

    /**
     * Get category data.
     *
     * @param int $categoryId
     * @return mixed
     * @throws Exception
     */
    public function getCategoryData(int $categoryId)
    {
        $result = $this->getSingleResponse('GET', function ($sessionId){
            return "categories/$sessionId?lang=$this->locale";
        });

        return collect($result)->keyBy('categoryID')->get($categoryId);
    }

    /**
     * Get category data multi language data.
     *
     * @param int $categoryId
     * @return array
     * @throws Exception
     */
    public function getCategoryMultiLanguageData(int $categoryId)
    {
        // execute queries
        $results = $this->getPoolResponses(function ($sessionId){
            return [
                'categories_ru' => new Request('GET', "categories/$sessionId?lang=ru"),
                'categories_ua' => new Request('GET', "categories/$sessionId?lang=ua"),
            ];
    });

        // retrieve 'ru' and 'ua' categories with given vendor category id
        $categoryData = [
            'category_data_ru' => collect($results['categories_ru'])->keyBy('categoryID')->get($categoryId),
            'category_data_ua' => collect($results['categories_ua'])->keyBy('categoryID')->get($categoryId),
        ];

        return $categoryData;
    }

    /**
     * @param int $categoryId
     * @param int $productsPerPage
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function getCategoryProductsData(int $categoryId, int $productsPerPage, int $offset): array
    {
        return $this->getPoolResponses(function ($sessionId) use ($categoryId, $productsPerPage, $offset){
            // collect uri for pool requests with given session id
            return [
                'products' => new Request('GET', "products/$categoryId/$sessionId?limit=$productsPerPage&offset=$offset&lang=$this->locale"),
                'course' => new Request('GET', "currencies/$sessionId"),
            ];
        });
    }

    /**
     * Get products ids for given page.
     *
     * @param int $categoryId
     * @param int $productsPerPage
     * @param int $offset
     * @return stdClass
     * @throws Exception
     */
    public function getCategoryProductsIds(int $categoryId, int $productsPerPage, int $offset): stdClass
    {
        return $this->getSingleResponse('GET', function ($sessionId) use ($categoryId, $productsPerPage, $offset){
            return "products/$categoryId/$sessionId?limit=$productsPerPage&offset=$offset&lang=$this->locale";
        });
    }
}
