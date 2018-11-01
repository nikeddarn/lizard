<?php
/**
 * Get rates from finance.ua.
 */

namespace App\Support\ExchangeRates;

use SimpleXMLElement;

class FinanceExchangeRates
{
    const RATE_SOURCE_URL = 'http://resources.finance.ua/ru/public/currency-cash.xml';

    const CURL_TIMEOUT = 500;

    /**
     * Get average rate of given sources.
     *
     * @return float|null
     */
    public function getRate()
    {
        $ratesData = $this->getDataFromSource();

        if (!$ratesData){
            return null;
        }

        $rateElements = $ratesData->xpath('/source/organizations/organization/currencies/c[@id="USD"]');

        $rateSourcesCount = count($rateElements);

        if (!$rateSourcesCount) {
            return null;
        }

        $sumRate = 0;

        foreach ($rateElements as $rateSource) {
            $sumRate += (float)$rateSource->attributes()->ar;
        }

        return $sumRate / $rateSourcesCount;
    }

    /**
     * Get rates data from source
     *
     * @return null|SimpleXMLElement
     */
    private function getDataFromSource(){
        $curl = curl_init(self::RATE_SOURCE_URL);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, self::CURL_TIMEOUT);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response ? new SimpleXMLElement($response) : null;
    }
}