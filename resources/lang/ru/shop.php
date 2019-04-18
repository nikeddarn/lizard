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

    'product' => [
      'reviews' => '{1} отзыв|[2,4] отзыва|[5,20] отзывов|{21} отзыв|[22,24] отзыва|[25,30] отзывов|{31} отзыва|[32,34] отзыва|[35,40] отзывов|{41} отзыв|[42,44] отзыва|[45,50] отзывов|{51} отзыв|[52,54] отзыва|[55,60] отзывов|{61} отзыв|[62,64] отзыва|[65,70] отзывов|{71} отзыв|[72,74] отзыва|[75,80] отзывов|{81} отзыв|[82,84] отзыва|[85,90] отзывов|{91} отзыв|[92,94] отзыва|[95,100] отзывов',

        'defect' => '{1} возврат|[2,4] возврата|[5,20] возвратов|{21} возврат|[22,24] возврата|[25,30] возвратов|{31} возврат|[32,34] возврата|[35,40] возвратов|{41} возврат|[42,44] возврата|[45,50] возвратов|{51} возврат|[52,54] возврата|[55,60] возвратов|{61} возврат|[62,64] возврата|[65,70] возвратов|{71} возврат|[72,74] возврата|[75,80] возвратов|{81} возврат|[82,84] возврата|[85,90] возвратов|{91} возврат|[92,94] возврата|[95,100] возвратов',
    ],
];
