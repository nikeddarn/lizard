<?php
/**
 * Get product data.
 */

namespace App\Support\Vendors\Providers;


use Exception;
use stdClass;

class BrainUpdateProductProvider extends BrainProvider
{
    /**
     * Get product data.
     *
     * @param int $vendorProductId
     * @return stdClass
     * @throws Exception
     */
    public function getProductData(int $vendorProductId): stdClass
    {
        return $this->getSingleResponse('GET', function ($sessionId) use ($vendorProductId) {
            return "product/$vendorProductId/$sessionId?lang=ru";
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
        return $this->getSingleResponse('GET', function ($sessionId) {
            return "currencies/$sessionId";
        });
    }

    /**
     * Get product content data.
     *
     * @param int $vendorProductId
     * @return mixed
     * @throws Exception
     */
    public function getContentData(int $vendorProductId)
    {
        return $this->getSingleResponse('POST', function ($sessionId) use ($vendorProductId) {
            return "products/content/$sessionId?lang=ru";
        }, ['body' => "lang=ru&productIDs=$vendorProductId"]);
    }
}
