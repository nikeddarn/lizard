<?php

/**
 * Defined product attributes model items.
 */

use App\Contracts\Order\DeliveryTypesInterface;

return [
    [
        'interface_id' => DeliveryTypesInterface::SELF,
        'name_ru' => 'Самовывоз',
        'name_uk' => 'Самовивіз',
    ],
    [
        'interface_id' => DeliveryTypesInterface::COURIER,
        'name_ru' => 'Курьерская доставка',
        'name_uk' => 'Кур\'єрська доставка',
    ],
    [
        'interface_id' => DeliveryTypesInterface::POST,
        'name_ru' => 'Отправка почтой',
        'name_uk' => 'Відправка поштою',
    ],
];
