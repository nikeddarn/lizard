<?php
/**
 * Get modified products.
 */

namespace App\Support\Vendors\Providers;


use Carbon\Carbon;
use Exception;

class BrainSyncUpdatedProductProvider extends BrainProvider
{
    /**
     * @var string
     */
    private $vendorSynchronizedAt;

    /**
     * Get modified vendor products ids.
     *
     * @param string|null $lastSynchronizedAt
     * @return array
     * @throws Exception
     */
    public function getUpdatedProductsIds(string $lastSynchronizedAt = null): array
    {
        // collect products ids
        $productsIds = [];

        $page = 0;

        do {
            $result = $this->getSingleResponse('GET', function ($sessionId) use ($lastSynchronizedAt, $page) {
                return "modified_products/$sessionId?limit=" . self::MODIFIED_PRODUCTS_LIMIT
                    . "&offset=" . (self::MODIFIED_PRODUCTS_LIMIT * $page)
                    . ($lastSynchronizedAt ? "&modified_time=$lastSynchronizedAt" : "");
            });

            // add current modified products
            $productsIds = array_merge($productsIds, $result->productIDs);

            // synchronized time
            if (!$this->vendorSynchronizedAt) {
                $this->vendorSynchronizedAt = $result->current;
            }

            $page++;

        } while ($result->count > self::MODIFIED_PRODUCTS_LIMIT * ($page - 1));

        return $productsIds;
    }

    /**
     * Get new vendor synchronization time.
     *
     * @return string
     */
    public function getVendorSynchronizedAt()
    {
        return $this->vendorSynchronizedAt ? $this->vendorSynchronizedAt : Carbon::now()->toDateTimeString();
    }
}
