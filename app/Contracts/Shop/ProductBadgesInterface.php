<?php
/**
 * Define product badges.
 */

namespace App\Contracts\Shop;


interface ProductBadgesInterface
{
    const NEW = 1;

    const PRICE_DOWN = 2;

    const ACTION = 3;

    const ENDING = 4;
}