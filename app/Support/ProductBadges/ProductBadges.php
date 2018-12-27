<?php
/**
 * Product badge broker.
 */

namespace App\Support\ProductBadges;


use App\Models\Product;
use Carbon\Carbon;

class ProductBadges
{
    /**
     * Insert product badges.
     *
     * @param Product $product
     * @param array $badges
     */
    public function insertProductBadges(Product $product, array $badges)
    {
        foreach ($badges as $badgeId) {
            $this->insertProductBadge($product, $badgeId);
        }
    }

    /**
     * Insert product badge.
     *
     * @param Product $product
     * @param int $badgeId
     */
    public function insertProductBadge(Product $product, int $badgeId)
    {
        $badgeTTL = config('shop.badges.ttl.' . $badgeId);
        $badgeExpired = $badgeTTL ? Carbon::now()->addDays($badgeTTL) : null;

        $product->badges()->syncWithoutDetaching([
            $badgeId => [
                'expired' => $badgeExpired,
            ],
        ]);
    }

    /**
     * Delete product badges.
     *
     * @param Product $product
     * @param array $badges
     */
    public function deleteProductBadges(Product $product, array $badges)
    {
        $product->badges()->detach($badges);
    }

    /**
     * Delete product badges.
     *
     * @param Product $product
     * @param int $badgeId
     */
    public function deleteProductBadge(Product $product, int $badgeId)
    {
        $product->badges()->detach($badgeId);
    }
}
