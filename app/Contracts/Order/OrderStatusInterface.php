<?php
/**
 * Delivery types
 */

namespace App\Contracts\Order;


interface OrderStatusInterface
{
    const HANDLING = 1;

    const COLLECTING = 2;

    const COLLECTED = 3;

    const DELIVERING = 4;

    const DELIVERED = 5;

    const CANCELLED = 6;
}
