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
        ShowProductsInterface::GRID => 'Сітка',
        ShowProductsInterface::LIST => 'Список',
    ],

    'sort_products_method' => [
        SortProductsInterface::POPULAR => 'За популярністю',
        SortProductsInterface::LOW_TO_HIGH => 'За зростанням ціни',
        SortProductsInterface::HIGH_TO_LOW => 'За зменшенням ціни',
        SortProductsInterface::RATING => 'За рейтингом',
    ],

    'product' => [
      'reviews' => '{1} відгук|[2,4] відгука|[5,20] відгуків|{21} відгук|[22,24] відгука|[25,30] відгуків|{31} відгука|[32,34] відгука|[35,40] відгуків|{41} відгук|[42,44] відгука|[45,50] відгуків|{51} відгук|[52,54] відгука|[55,60] відгуків|{61} відгук|[62,64] відгука|[65,70] відгуків|{71} відгук|[72,74] відгука|[75,80] відгуків|{81} відгук|[82,84] відгука|[85,90] відгуків|{91} відгук|[92,94] відгука|[95,100] відгуків',

        'defect' => '{1} повернення|[2,4] повернення|[5,20] повернень|{21} повернення|[22,24] повернення|[25,30] повернень|{31} повернення|[32,34] повернення|[35,40] повернень|{41} повернення|[42,44] повернення|[45,50] повернень|{51} повернення|[52,54] повернення|[55,60] повернень|{61} повернення|[62,64] повернення|[65,70] повернень|{71} повернення|[72,74] повернення|[75,80] повернень|{81} повернення|[82,84] повернення|[85,90] повернень|{91} повернення|[92,94] повернення|[95,100] повернень',
    ],

    'order' => [
        'created' => [
            'subject' => 'Замовлення прийнято'
        ],

        'updated' => [
            'subject' => 'замовлення змінено',
        ],

        'deleted' => [
            'subject' => 'Замовлення скасовано',
        ],
    ],
];
