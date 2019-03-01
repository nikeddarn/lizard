<?php

use App\Contracts\Shop\ProductBadgesInterface;

return [

    'ttl' => [
        // badges ttl in days
        ProductBadgesInterface::NEW => 5,
        ProductBadgesInterface::PRICE_DOWN => 2,
    ],

    // count of products
    ProductBadgesInterface::ENDING => 5,
];
