<?php
/**
 * Vendor config data.
 */

use App\Contracts\Vendor\VendorInterface;

return [
    'insert_product' => [
        // download archive product
        'download_archive_product' => false,
    ],

    'delete_product' => [
        // delete products when deleting its vendor category
        'delete_product_on_delete_vendor_category' => true,

        // unlink product that present in stock from vendor when deleting its vendor category
        'keep_link_in_stock_present_product_on_delete' => true,

        // delete empty local category on delete vendor category
        'delete_empty_local_category_on_delete_vendor_category' => true,

        // delete (or archive) product when vendor product set archive
        'delete_product_on_archive_vendor_product' => false,
    ],

    'product_price_conditions' => [
        // update own storage product price on sync this product with vendor
        'update_own_product_price_on_vendor_sync' => true,

        // use only price of products that present on vendor storage
        'use_vendor_available_product_to_calculate_price' => false,
    ],

    'price_discount' => [
        // min profit sum to use discount for columns ($)
        'min_profit_sum_to_price_discount' => 10,

        // min profit percents to use discount for columns (%)
        'min_profit_percents_to_price_discount' => 5,

        // discount profit from vendor products for columns
        'column_discounts' => [
            'price1' => 0,
            'price2' => 20,
            'price3' => 50,
        ],
    ],

    // search double on insert new entity.
    'search_double_by' => [
        // set of product model fields that must be equal to get product as double of existing
        'product' => [
            // fields in arrays applies with 'AND' operation
            ['name_ru', 'manufacturer_ru'],
            ['name_uk', 'manufacturer_uk'],
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
        // define vendor product price aggregate method for multi vendors
        // possible: 'min', 'avg', 'max'
        'multi_vendor_aggregate_product_price_method' => 'avg',

        // using vendor product retail price column in preferred order
        'use_vendor_retail_price_column' => ['recommendable_price', 'retail_price'],
    ],

    // session id ttl (minutes)
    'vendor_session_id_ttl' => [
        VendorInterface::BRAIN => 30,
    ],
];
