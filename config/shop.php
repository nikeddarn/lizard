<?php

use App\Contracts\Shop\ProductBadgesInterface;
use App\Contracts\Vendor\VendorInterface;
use App\Support\ExchangeRates\FinanceExchangeRates;
use App\Support\ExchangeRates\PrivatBankExchangeRates;

return [

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

        // store original full vendor image
        'store_original_image' => false,

        // sizes for product image by types
        'products' => [
            // original image
            'image' => 2600,
            // shop images
            'small' => 100,
            'medium' => 400,
            'large' => 1200,

            // use watermark
            'watermark' => false,
        ],

        // category image size
        'category' => 300,

        // brand image size
        'brand' => 400,

        // description images
        'description' => 800,
    ],

    // search double on insert new entity.
    'search_double_by' => [
        // fields of product model
        'product' => [
            'name_ru', 'name_ua',
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
        // count of tries to insert product
        'tries' => 2,
        // max product comments count
        'max_comments' => 30,
    ],

    // update vendor product price
    'update_vendor_product' => [
        // retry after (minutes)
        'retry' => 30,
        // count of tries to insert product
        'tries' => 6,
    ],

    'price' => [
        // discount profit from vendor products for columns
        // use negative value for rise profit
        'vendor_column_price_discount' => [
            VendorInterface::BRAIN => [
                'price1' => 0,
                'price2' => 0.2,
                'price3' => 0.5,
            ],
        ],

        // min profit to use discount (%)
        'min_profit_to_price_discount' => 5,

        // update own storage product price on sync this product with vendor
        'update_own_product_price_on_vendor_sync' => true,

        // define vendor product price aggregate method for multi vendors
        // possible: 'min', 'avg', 'max'
        'multi_vendor_aggregate_product_price_method' => 'avg',

        // define aggregate method of product price discount for multi vendors
        // possible: 'min', 'avg', 'max'
        'multi_vendor_aggregate_column_price_discount_method' => 'avg',

        // take only prices of vendors that contains given product on storages
        'vendor_available_product_only' => false,

        // using vendor product retail price column in preferred order
        'use_vendor_retail_price_column' => ['recommendable_price', 'retail_price'],
    ],
];
