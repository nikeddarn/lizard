<?php
/**
 * Delivery types
 */

namespace App\Contracts\Order;


interface DeliveryTypesInterface
{
    const SELF = 1;

    const COURIER = 2;

    const POST = 3;
}
