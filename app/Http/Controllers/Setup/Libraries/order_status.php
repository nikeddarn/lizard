<?php

/**
 * Defined product attributes model items.
 */

use App\Contracts\Order\OrderStatusInterface;

return [
    [
        'interface_id' => OrderStatusInterface::HANDLING,
        'name_ru' => 'Обрабатывается',
        'name_uk' => 'Обробляється',
    ],
    [
        'interface_id' => OrderStatusInterface::COLLECTING,
        'name_ru' => 'Собирается',
        'name_uk' => 'Збирається',
    ],
    [
        'interface_id' => OrderStatusInterface::COLLECTED,
        'name_ru' => 'Готов к отгрузке',
        'name_uk' => 'Готовий до відвантаження',
    ],
    [
        'interface_id' => OrderStatusInterface::DELIVERING,
        'name_ru' => 'На доставке',
        'name_uk' => 'На доставці',
    ],
    [
        'interface_id' => OrderStatusInterface::DELIVERED,
        'name_ru' => 'Доставлен',
        'name_uk' => 'Доставлений',
    ],
    [
        'interface_id' => OrderStatusInterface::CANCELLED,
        'name_ru' => 'Отменен',
        'name_uk' => 'Скасовано',
    ],
];
