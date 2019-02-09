<?php

use App\Contracts\Shop\ShowProductsInterface;
use App\Contracts\Shop\SortProductsInterface;

return [

    // items count per page on shop pages
    'show_products_per_page' => 24,

    // product comments count per page
    'show_product_comments_per_page' => 8,

    // ttl in days to show user's recent product
    'recent_product_ttl' => 20,

    // show product rate
    'show_rate' => [
        'allowed' => true,
        // min reviews count to show product rating
        'count' => 1,
    ],

    // show product defect rate
    'show_defect_rate' => [
        'allowed' => true,
        // min sold product count to show defect rate
        'count' => 20,
    ],

    // redirect to user's preferred locale if referrer is external
    'redirect_user_to_preferred_locale' => true,


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
];
