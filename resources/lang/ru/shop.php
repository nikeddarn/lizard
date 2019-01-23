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
        ShowProductsInterface::GRID => 'Сетка',
        ShowProductsInterface::LIST => 'Список',
    ],

    'sort_products_method' => [
        SortProductsInterface::POPULAR => 'По популярности',
        SortProductsInterface::LOW_TO_HIGH => 'По возрастанию цены',
        SortProductsInterface::HIGH_TO_LOW => 'По убыванию цены',
        SortProductsInterface::RATING => 'По рейтингу',
    ],
];
