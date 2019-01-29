<?php
/**
 * Vendor config data.
 */

use App\Contracts\Vendor\VendorInterface;

return [
    // search double on insert new entity.
    'search_double_by' => [
        // fields of product model
        'product' => [
            'name_ru', 'name_uk',
        ],
        // fields of attribute model
        'attribute' => [
            'name_ru', 'name_uk',
        ],
        // fields of any attribute value model
        'attribute_value' => [
            'value_ru', 'value_uk', 'url',
        ],
        // fields of brand attribute value model
        'brand_attribute_value' => [
            'value_ru', 'value_uk', 'url',
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
