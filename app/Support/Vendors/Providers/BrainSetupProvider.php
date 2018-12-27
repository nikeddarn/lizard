<?php
/**
 * Brain setup provider.
 */

namespace App\Support\Vendors\Providers;


use Exception;

class BrainSetupProvider extends BrainProvider
{
    /**
     * Get vendors' brands.
     *
     * @return array
     * @throws Exception
     */
    public function getBrands(): array
    {
        return $this->getSingleResponse('GET', function ($sessionId) {
            return "vendors/$sessionId";
        });
    }

    /**
     * Get stocks.
     *
     * @return array
     * @throws Exception
     */
    public function getStocks(): array
    {
        return $this->getSingleResponse('GET', function ($sessionId) {
            return "stocks/$sessionId";
        });
    }
}
