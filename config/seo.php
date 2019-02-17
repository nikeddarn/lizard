<?php

use App\Contracts\Shop\LocalesInterface;

return [
    'metadata' => [

        'category' => [
            LocalesInterface::RU => [
                'title' => 'CATEGORY_NAME - Магазин ' . config('app.name') . ' - Купить в Киеве и Украине',
                'description' => 'CATEGORY_NAME - Широкий ассортимент в каталоге. Доставка, гарантия, сервисное обслуживание. &phone; +380-67-123-45-67',
                'keywords' => 'CATEGORY_NAME',
            ],
            LocalesInterface::UK => [
                'title' => 'CATEGORY_NAME - Магазин '. config ( 'app.name'). ' - Купити в Києві та Україні',
                'description' => 'CATEGORY_NAME - Широкий асортимент в каталозі. Доставка, гарантія, сервісне обслуговування. &phone; +380-67-123-45-67',
                'keywords' => 'CATEGORY_NAME',
            ],
        ],

        'leaf_category' => [
            LocalesInterface::RU => [
                'title' => 'CATEGORY_NAME - Магазин ' . config('app.name') . ' - Купить в Киеве и Украине',
                'description' => 'CATEGORY_NAME - Широкий ассортимент в каталоге. Доставка, гарантия, сервисное обслуживание. &phone; +380-67-123-45-67',
                'keywords' => 'CATEGORY_NAME',
            ],
            LocalesInterface::UK => [
                'title' => 'CATEGORY_NAME - Магазин '. config ( 'app.name'). ' - Купити в Києві та Україні',
                'description' => 'CATEGORY_NAME - Широкий асортимент в каталозі. Доставка, гарантія, сервісне обслуговування. &phone; +380-67-123-45-67',
                'keywords' => 'CATEGORY_NAME',
            ],
        ],

        'virtual_category' => [
            LocalesInterface::RU => [
                'name' => 'CATEGORY_NAME - FILTER_NAME: FILTER_VALUE',
                'title' => 'CATEGORY_NAME FILTER_VALUE - Магазин ' . config('app.name') . ' - Купить в Киеве и Украине',
                'description' => 'CATEGORY_NAME FILTER_VALUE - Широкий ассортимент в каталоге. Доставка, гарантия, сервисное обслуживание. &phone; +380-67-123-45-67',
                'keywords' => 'CATEGORY_NAME,FILTER_VALUE',
            ],
            LocalesInterface::UK => [
                'name' => 'CATEGORY_NAME - FILTER_NAME: FILTER_VALUE',
                'title' => 'CATEGORY_NAME FILTER_VALUE - Магазин '. config ( 'app.name'). ' - Купити в Києві та Україні',
                'description' => 'CATEGORY_NAME FILTER_VALUE - Широкий асортимент в каталозі. Доставка, гарантія, сервісне обслуговування. &phone; +380-67-123-45-67',
                'keywords' => 'CATEGORY_NAME,FILTER_VALUE',
            ],
        ],

        'filtered_category' => [
            LocalesInterface::RU => [
                'name' => 'CATEGORY_NAME',
                'title' => 'CATEGORY_NAME FILTERS_WITH_VALUES - Магазин ' . config('app.name') . ' - Купить в Киеве и Украине',
                'description' => 'CATEGORY_NAME FILTERS_WITH_VALUES - Широкий ассортимент в каталоге. Доставка, гарантия, сервисное обслуживание. &phone; +380-67-123-45-67',
                'keywords' => 'CATEGORY_NAME,FILTERS_VALUES',
            ],
            LocalesInterface::UK => [
                'name' => 'CATEGORY_NAME',
                'title' => 'CATEGORY_NAME FILTERS_WITH_VALUES - Магазин '. config ( 'app.name'). ' - Купити в Києві та Україні',
                'description' => 'CATEGORY_NAME FILTERS_WITH_VALUES - Широкий асортимент в каталозі. Доставка, гарантія, сервісне обслуговування. &phone; +380-67-123-45-67',
                'keywords' => 'CATEGORY_NAME,FILTERS_VALUES',
            ],
        ],

        'product' => [
            LocalesInterface::RU => [
                'title' => 'PRODUCT_NAME - Купить в Киеве и Украине - Магазин ' . config ( 'app.name'),
                'description' => 'PRODUCT_NAME[, производство - PRODUCT_MANUFACTURER][, гарантия - PRODUCT_WARRANTY мес]. Купить[ по цене PRODUCT_PRICE грн] в интернет-магазине ' . config ( 'app.name') . ' &phone; +380-67-123-45-67. Доставка курьером по Киеву и Украине.',
                'keywords' => 'PRODUCT_NAME,PRODUCT_MODEL,PRODUCT_ARTICUL',
            ],
            LocalesInterface::UK => [
                'title' => 'PRODUCT_NAME Купити в Києві та Україні - Магазин ' . config ( 'app.name'),
                'description' => 'PRODUCT_NAME[, виробництво - PRODUCT_MANUFACTURER][, гарантія - PRODUCT_WARRANTY міс]. Купити[ за ціною PRODUCT_PRICE грн] в інтернет-магазині ' . config ( 'app.name') . ' &phone; +380-67-123-45-67. Доставка кур\'єром по Києву і Україні.',
                'keywords' => 'PRODUCT_NAME,PRODUCT_MODEL,PRODUCT_ARTICUL',
            ],
        ],
    ],

    'changefreq' => [
        'static' => 'monthly',
        'category' => 'weekly',
        'leaf_category' => 'daily',
        'product' => 'daily',
    ]
];
