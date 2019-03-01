<?php

/**
 * Badges model items.
 */

use App\Contracts\Shop\ProductBadgesInterface;

return [
    [
        'id' => ProductBadgesInterface::NEW,
        'name_ru' => 'Новинка',
        'name_uk' => 'Новинка',
        'class' => 'primary',
    ],

    [
        'id' => ProductBadgesInterface::PRICE_DOWN,
        'name_ru' => 'Скидка',
        'name_uk' => 'Знижка',
        'class' => 'success',
    ],

    [
        'id' => ProductBadgesInterface::ACTION,
        'name_ru' => 'Акция',
        'name_uk' => 'Акція',
        'class' => 'warning',
    ],

    [
        'id' => ProductBadgesInterface::ENDING,
        'name_ru' => 'Заканчивается',
        'name_uk' => 'Закінчується',
        'class' => 'danger',
    ],

    [
        'id' => ProductBadgesInterface::ARCHIVE,
        'name_ru' => 'Архивный',
        'name_uk' => 'Архівний',
        'class' => 'danger',
    ],
];
