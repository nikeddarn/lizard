<?php

/**
 * Defined product attributes model items.
 */

use App\Contracts\Order\DeliveryTypesInterface;

return [
    [
        'id' => DeliveryTypesInterface::SELF,
        'name_ru' => 'Самовывоз',
        'name_uk' => 'Самовивіз',
    ],
    [
        'id' => DeliveryTypesInterface::COURIER,
        'name_ru' => 'Курьерская доставка',
        'name_uk' => 'Кур\'єрська доставка',
    ],
    [
        'id' => DeliveryTypesInterface::POST,
        'name_ru' => 'Отправка почтой',
        'name_uk' => 'Відправка поштою',
    ],
];
