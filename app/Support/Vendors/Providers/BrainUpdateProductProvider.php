<?php
/**
 * Get product data.
 */

namespace App\Support\Vendors\Providers;


use Exception;
use GuzzleHttp\Psr7\Request;

class BrainUpdateProductProvider extends BrainProvider
{

    /**
     * Get product data.
     *
     * @param int $vendorProductId
     * @return array
     * @throws Exception
     */
    public function getProductData(int $vendorProductId): array
    {
        return $this->getPoolResponses(function ($sessionId) use ($vendorProductId) {
            return [
                'product' => new Request('GET', "product/$vendorProductId/$sessionId?lang=ru"),

                'course' => new Request('GET', "currencies/$sessionId"),
            ];
        });
    }
}
