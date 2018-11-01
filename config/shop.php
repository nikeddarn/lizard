<?php

use App\Contracts\Shop\ProductBadgesInterface;
use App\Support\ExchangeRates\FinanceExchangeRates;
use App\Support\ExchangeRates\PrivatBankExchangeRates;

return [

    // items count per page on shop pages
    'show_items_per_page' => 12,

    // product comments count per page
    'show_product_comments_per_page' => 6,

    'exchange_rate' => [
        // sources in preferred order
        'sources' => [
            PrivatBankExchangeRates::class,
            FinanceExchangeRates::class,
        ],
        // ttl in minutes
        'ttl' => 180,
    ],

    // min count of operation with product for show rate
    'min_quantity_to_show_rate' => [
        // min quantity of reviews to show product rating
        'product' => 5,
        // min sold product quantity to show defect rate
        'defect' => 20,
    ],

    // badges ttl in days
    'badges' => [
        'ttl' => [
            ProductBadgesInterface::NEW => 10,
            ProductBadgesInterface::PRICE_DOWN => 5,
        ],
        'count' => [
            ProductBadgesInterface::ENDING => 5,
        ]
    ],

    // ttl in days to show user's recent product
    'recent_product_ttl' => 20,

    // images
    'image' => [
        // transform image aspect ratio to this value
        'ratio' => [
            'use' => true,
            'value' => 1.3333
        ],

        // rotate if width > height
        'rotate' => true,

        // width of images in px
        'width' => [
            'small' => 100,
            'medium' => 200,
            'large' => 1200,
        ],

        // watermark
        'watermark' => [
            'use' => true,
            'color' => [
                'r' => 48,
                'g' => 161,
                'b' => 147,
                // alpha in %
                'a' => 90,
            ],
            // from 0 to 1
            'left' => 0.1,
            'top' => 0.8,
        ],
    ]
];