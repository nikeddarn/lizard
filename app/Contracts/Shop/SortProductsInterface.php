<?php
/**
 * Constants to define sort products by.
 */

namespace App\Contracts\Shop;


interface SortProductsInterface
{
    const POPULAR = 'popular';

    const LOW_TO_HIGH = 'low-to-high';

    const HIGH_TO_LOW = 'high-to-low';

    const RATING = 'rating';
}
