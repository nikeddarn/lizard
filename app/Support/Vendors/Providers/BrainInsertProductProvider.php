<?php
/**
 * Insert new Brain product provider.
 */

namespace App\Support\Vendors\Providers;

use Exception;
use GuzzleHttp\Psr7\Request;

class BrainInsertProductProvider extends BrainProvider
{
    /**
     * Get all product data for insert new product.
     *
     * @param int $productId
     * @return array
     * @throws Exception
     */
    public function getProductData(int $productId): array
    {
        // execute queries
        return $this->getPoolResponses(function ($sessionId) use ($productId) {
            return [
                'product_data_ru' => new Request('GET', "product/$productId/$sessionId?lang=ru"),

                'product_content_data_ru' => new Request('POST', "products/content/$sessionId?lang=ru", [],
                    "lang=ru&productIDs=$productId"),

                'product_content_data_uk' => new Request('POST', "products/content/$sessionId?lang=ua", [], "lang=ua&productIDs=$productId"),

                'comments' => new Request('POST', "comments/$productId/$sessionId"),
            ];
        });
    }

    /**
     * Get Brain currencies courses.
     *
     * @return mixed
     * @throws Exception
     */
    public function getCoursesData()
    {
        return $this->getSingleResponse('GET', function ($sessionId){
            return "currencies/$sessionId";
        });
    }
}
