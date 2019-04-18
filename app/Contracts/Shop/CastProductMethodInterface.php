<?php
/**
 * Created by PhpStorm.
 * User: nikeddarn
 * Date: 11.03.19
 * Time: 15:28
 */

namespace App\Contracts\Shop;


interface CastProductMethodInterface
{
    const NEW = 1;

    const PRICE_DOWN = 2;

    const POPULAR = 3;

    const RANDOM = 4;
}
