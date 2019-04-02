<?php

/**
 * Defined product attributes model items.
 */

use App\Contracts\Order\OrderStatusInterface;

return [
    [
        'id' => OrderStatusInterface::HANDLING,
        'name_ru' => 'Обрабатывается',
        'name_uk' => 'Обробляється',
        'class' => 'warning',
    ],
    [
        'id' => OrderStatusInterface::COLLECTING,
        'name_ru' => 'Собирается',
        'name_uk' => 'Збирається',
        'class' => 'info',
    ],
    [
        'id' => OrderStatusInterface::COLLECTED,
        'name_ru' => 'Готов к отгрузке',
        'name_uk' => 'Готовий до відвантаження',
        'class' => 'info',
    ],
    [
        'id' => OrderStatusInterface::DELIVERING,
        'name_ru' => 'На доставке',
        'name_uk' => 'На доставці',
        'class' => 'info',
    ],
    [
        'id' => OrderStatusInterface::DELIVERED,
        'name_ru' => 'Отгружен',
        'name_uk' => 'Відвантажено',
        'class' => 'success',
    ],
    [
        'id' => OrderStatusInterface::CANCELLED,
        'name_ru' => 'Отменен',
        'name_uk' => 'Скасовано',
        'class' => 'danger',
    ],
];
