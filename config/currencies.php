<?php

use App\Support\ExchangeRates\FinanceExchangeRates;
use App\Support\ExchangeRates\PrivatBankExchangeRates;

return [
    // sources in preferred order
    'exchange_rate_sources' => [
        PrivatBankExchangeRates::class,
        FinanceExchangeRates::class,
    ],

    // get exchange rate
    'exchange_rate' => [
        // get rate ('auto' or 'manual')
        'get_exchange_rate' => 'auto',

        // manual set rate
        'usd_rate' => null,

        // ttl in minutes
        'ttl' => 180,
    ],

    // show USD price
    'show_usd_price' => [
        // is usd price allowed to show
        'allowed' => true,
        // min user price group to show Usd price
        'min_user_price_group' => 3,
    ],
];
