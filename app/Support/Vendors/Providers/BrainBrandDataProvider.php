<?php
/**
 * Brain brand data provider.
 */

namespace App\Support\Vendors\Providers;


use Exception;
use stdClass;

class BrainBrandDataProvider extends BrainProvider
{
    /**
     * Get brand data by vendor's brand id.
     *
     * @param int $vendorBrandId
     * @return stdClass
     * @throws Exception
     */
    public function getBrandData(int $vendorBrandId): stdClass
    {
        $brands = $this->getSingleResponse('GET', function ($sessionId) {
            return "vendors/$sessionId";
        });

        return collect($brands)->keyBy('vendorID')->get($vendorBrandId);
    }
}
