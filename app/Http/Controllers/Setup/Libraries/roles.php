<?php

/**
 * User role model items.
 */

use App\Contracts\Auth\RoleInterface;

return [
    [
        'id' => RoleInterface::ADMIN,
        'title_ru' => 'Администратор',
        'title_ua' => 'Адміністратор',
    ],
    [
        'id' => RoleInterface::USER_MANAGER,
        'title_ru' => 'Менеджер по продажам',
        'title_ua' => 'Менеджер з продажу
',
    ],
    [
        'id' => RoleInterface::VENDOR_MANAGER,
        'title_ru' => 'Менеджер по закупкам',
        'title_ua' => 'Менеджер по закупці',
    ],
    [
        'id' => RoleInterface::STOREKEEPER,
        'title_ru' => 'Кладовщик',
        'title_ua' => 'Комірник',
    ],
    [
        'id' => RoleInterface::SERVICEMAN,
        'title_ru' => 'Менеджер по гарантии',
        'title_ua' => 'Менеджер по гарантії',
    ],

];