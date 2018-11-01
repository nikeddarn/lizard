<?php

/**
 * Badges model items.
 */

use App\Contracts\Shop\ProductBadgesInterface;

return [
    [
        'id' => ProductBadgesInterface::NEW,
        'name_ru' => 'Новинка',
        'name_ua' => 'Новинка',
        'class' => 'primary',
    ],

    [
        'id' => ProductBadgesInterface::PRICE_DOWN,
        'name_ru' => 'Скидка',
        'name_ua' => 'Знижка',
        'class' => 'success',
    ],

    [
        'id' => ProductBadgesInterface::ACTION,
        'name_ru' => 'Акция',
        'name_ua' => 'Акція',
        'class' => 'warning',
    ],

    [
        'id' => ProductBadgesInterface::ENDING,
        'name_ru' => 'Заканчивается',
        'name_ua' => 'Закінчується',
        'class' => 'danger',
    ],


];