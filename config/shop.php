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
    'images' => [

        'products' => [
            'small' => [
                'w' => 100,
                'h' => 100,
            ],
            'medium' => [
                'w' => 300,
                'h' => 400,
            ],
            'large' => [
                'w' => 1050,
                'h' => 1400,
            ],

            'watermark' => true,
        ],

        'category' => [
            'w' => 300,
            'h' => 400,
        ],

        'brand' => [
            'w' => 400,
            'h' => 300,
        ],

        'watermark' => [
            'font' => 'fonts/OpenSans-Regular.ttf',
            'size' => 0.2,
            'color' => [48, 161, 147, 0.2],
            'align' => 'center',
            'valign' => 'bottom',
            'angle' => 0,
            'baseX' => 0.1,
            'baseY' => 0.8,
        ],
    ],

    // discount profit from vendor products for columns
    // use negative value for rise profit
    'vendor_price_discount' => [
        'price1' => 0,
        'price2' => 0.2,
        'price3' => 0.5,
    ],

    // search double on insert new entity.
    'search_double_by' => [
        // fields of product model
        'product' => [
            'name_ru', 'name_ua', 'model_ru', 'model_ua', 'articul', 'code',
        ],
        // fields of attribute model
        'attribute' => [
            'name_ru', 'name_ua',
        ],
        // fields of any attribute value model
        'attribute_value' => [
            'value_ru', 'value_ua', 'url',
        ],
        // fields of brand attribute value model
        'brand_attribute_value' => [
            'value_ru', 'value_ua', 'url',
        ],
    ],

    // insert new vendor products
    'insert_vendor_product' => [
        // retry after (minutes)
        'retry' => 60,
    ]
];