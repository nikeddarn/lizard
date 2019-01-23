<?php

use App\Contracts\Shop\LocalesInterface;
use App\Contracts\Shop\ShowProductsInterface;
use App\Contracts\Shop\SortProductsInterface;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'locale' => [
        LocalesInterface::RU => 'RU',
        LocalesInterface::UK => 'UA',
    ],

    'show_products_method' => [
        ShowProductsInterface::GRID => 'Show grid',
        ShowProductsInterface::LIST => 'Show list',
    ],

    'sort_products_method' => [
        SortProductsInterface::POPULAR => 'Sort by popular',
        SortProductsInterface::LOW_TO_HIGH => 'Low to high price',
        SortProductsInterface::HIGH_TO_LOW => 'High to low price',
        SortProductsInterface::RATING => 'Sort by rating',
    ],
];
