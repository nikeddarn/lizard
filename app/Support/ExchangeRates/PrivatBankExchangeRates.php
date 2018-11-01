<?php
/**
 * Get rates from Privat Bank.
 */

namespace App\Support\ExchangeRates;

use SimpleXMLElement;

class PrivatBankExchangeRates
{
    const RATE_SOURCE_URL = 'https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=5';

    const CURL_TIMEOUT = 500;

    /**
     * Get rate of given sources.
     *
     * @return float|null
     */
    public function getRate()
    {
        $ratesData = $this->getDataFromSource();

        if (!$ratesData){
            return null;
        }

        $usdRates = $ratesData->xpath('/exchangerates/row/exchangerate[@ccy="USD"]');

        return isset($usdRates[0]['sale']) ? (float)$usdRates[0]['sale'] : null;
    }


    /**
     * Get rates data from source
     *
     * @return null|SimpleXMLElement
     */
    private function getDataFromSource()
    {
        $curl = curl_init(self::RATE_SOURCE_URL);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, self::CURL_TIMEOUT);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response ? new SimpleXMLElement($response) : null;
    }
}