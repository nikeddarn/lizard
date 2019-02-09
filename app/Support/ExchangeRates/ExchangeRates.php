<?php
/**
 * Get exchange rates.
 */

namespace App\Support\ExchangeRates;

use App\Support\Settings\SettingsRepository;
use Illuminate\Support\Facades\Cache;

class ExchangeRates
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * ExchangeRates constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Get rate from storage or external sources.
     *
     * @return mixed
     */
    public function getRate()
    {
        $exchangeRateSettings = $this->settingsRepository->getProperty('currencies.exchange_rate');

        if ($exchangeRateSettings['get_exchange_rate'] === 'manual' && !empty($exchangeRateSettings['usd_rate'])) {
            // get manual set of rate
            return $exchangeRateSettings['usd_rate'];
        }else{
            // get rate ttl
            $exchangeRateTtl = $exchangeRateSettings['ttl'];

            return Cache::remember('exchangeRate', $exchangeRateTtl, function () {
                // get current rate from external sources
                $currentRate = $this->defineExchangeRate();

                if ($currentRate) {
                    // store current rate as reserved
                    Cache::forever('reservedExchangeRate', $currentRate);

                    return $currentRate;
                } else {
                    // get reserved exchange rate
                    return Cache::get('reservedExchangeRate');
                }
            });
        }
    }

    /**
     * Define exchange rates from sources.
     *
     * @return float|null
     */
    private function defineExchangeRate()
    {
        foreach (config('currencies.exchange_rate_sources') as $source) {
            $rate = (new $source)->getRate();
            if ($rate) {
                return $rate;
            }
        }

        return null;
    }
}
