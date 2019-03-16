<?php

/**
 * User role model items.
 */

use App\Contracts\Auth\RoleInterface;

return [
    [
        'id' => RoleInterface::ADMIN,
        'title_ru' => 'Администратор',
        'title_uk' => 'Адміністратор',
    ],
    [
        'id' => RoleInterface::CONTENT_MANAGER,
        'title_ru' => 'Контент менеджер',
        'title_uk' => 'Контент менеджер',
    ],
    [
        'id' => RoleInterface::USER_MANAGER,
        'title_ru' => 'Менеджер по продажам',
        'title_uk' => 'Менеджер з продажу
',
    ],
    [
        'id' => RoleInterface::STOREKEEPER,
        'title_ru' => 'Кладовщик',
        'title_uk' => 'Комірник',
    ],
    [
        'id' => RoleInterface::SERVICEMAN,
        'title_ru' => 'Менеджер по гарантии',
        'title_uk' => 'Менеджер по гарантії',
    ],

];
