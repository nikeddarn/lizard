<?php
/**
 * Product badge broker.
 */

namespace App\Support\ProductBadges;


use App\Models\Product;
use App\Support\Settings\SettingsRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductBadges
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * ProductBadges constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

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
     * @param Product|Model $product
     * @param int $badgeId
     */
    public function insertProductBadge(Product $product, int $badgeId)
    {
        $badgesSettings = $this->settingsRepository->getProperty('badges');

        $badgeExpired = isset($badgesSettings['ttl'][$badgeId]) ? Carbon::now()->addDays($badgesSettings['ttl'][$badgeId]) : null;

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
