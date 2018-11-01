<?php
/**
 * Get exchange rates.
 */

namespace App\Support\ExchangeRates;

use Illuminate\Support\Facades\Cache;

class ExchangeRates
{
    public function getRate()
    {
        return Cache::remember('exchangeRate', config('shop.exchange_rate.ttl'), function () {
            return $this->defineExchangeRate();
        });
    }

    /**
     * Define exchange rates from sources.
     *
     * @return float|null
     */
    private function defineExchangeRate()
    {
        foreach (config('shop.exchange_rate.sources') as $source) {
            $rate = (new $source)->getRate();
            if ($rate) {
                return $rate;
            }
        }

        return null;
    }
}