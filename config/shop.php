<?php

use App\Contracts\Shop\ProductBadgesInterface;
use App\Contracts\Shop\ShowProductsInterface;
use App\Contracts\Shop\SortProductsInterface;
use App\Support\ExchangeRates\FinanceExchangeRates;
use App\Support\ExchangeRates\PrivatBankExchangeRates;

return [

    // redirect to user's preferred locale if referrer is external
    'redirect_user_to_preferred_locale' => true,

    // items count per page on shop pages
    'show_items_per_page' => 24,

    // product comments count per page
    'show_product_comments_per_page' => 8,

    'exchange_rate' => [
        // sources in preferred order
        'sources' => [
            PrivatBankExchangeRates::class,
            FinanceExchangeRates::class,
        ],
        // ttl in minutes
        'ttl' => 180,
    ],

    // sort products method
    'products_sort' => [

        // available sorts methods
        'possible_sort_methods' => [
            SortProductsInterface::POPULAR,
            SortProductsInterface::LOW_TO_HIGH,
            SortProductsInterface::HIGH_TO_LOW,
            SortProductsInterface::RATING,
        ],

        // seo canonical sort method
        'canonical_sort_method' => SortProductsInterface::POPULAR,
    ],

    // show products method
    'products_show' => [
        // available show methods
        'possible_show_methods' => [
            ShowProductsInterface::GRID,
            ShowProductsInterface::LIST,
        ],

        // seo canonical show method
        'canonical_show_method' => ShowProductsInterface::GRID,
    ],

    // min count of votes for product for show rate
    'min_quantity_to_show_rate' => [
        // min quantity of reviews to show product rating
        'product' => 5,
        // min sold product quantity to show defect rate
        'defect' => 20,
    ],

    // badges ttl in days
    'badges' => [
        'ttl' => [
            ProductBadgesInterface::NEW => 5,
            ProductBadgesInterface::PRICE_DOWN => 2,
        ],
        'count' => [
            ProductBadgesInterface::ENDING => 5,
        ]
    ],

    // ttl in days to show user's recent product
    'recent_product_ttl' => 20,

    // show USD price
    'show_usd_price' => [
        // is usd price allowed to show
        'allowed' => true,
        // min user price group to show Usd price
        'min_user_price_group' => 3,
    ],
];
