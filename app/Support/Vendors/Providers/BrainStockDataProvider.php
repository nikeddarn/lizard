<?php
/**
 * Brain stock data provider.
 */

namespace App\Support\Vendors\Providers;


use Exception;
use stdClass;

class BrainStockDataProvider extends BrainProvider
{
    /**
     * Get stock data by vendor's stock id.
     *
     * @param int $vendorStockId
     * @return stdClass
     * @throws Exception
     */
    public function getStockData(int $vendorStockId): stdClass
    {
        $stocks = $this->getSingleResponse('GET', function ($sessionId) {
            return "stocks/$sessionId";
        });

        return collect($stocks)->keyBy('stockID')->get($vendorStockId);
    }
}
