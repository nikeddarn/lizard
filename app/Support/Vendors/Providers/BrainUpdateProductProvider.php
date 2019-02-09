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
}
