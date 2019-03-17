<?php
/**
 * Delivery price.
 */

namespace App\Support\Orders;


use App\Support\Settings\SettingsRepository;

class DeliveryPrice
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * DeliveryPrice constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {

        $this->settingsRepository = $settingsRepository;
    }
    /**
     * Calculate delivery price.
     *
     * @param $user
     * @param float $orderAmount
     * @return int
     */
    public function calculateDeliveryPrice($user, float $orderAmount):int
    {
        $deliverySettings = $this->settingsRepository->getProperty('shop.order.delivery');

        $freeDeliveryFromPriceGroup = $deliverySettings['free_delivery_from_column'];
        if ($freeDeliveryFromPriceGroup !== null && $user->price_group >= $freeDeliveryFromPriceGroup){
            return 0;
        }

        $freeDeliveryFromSum = $deliverySettings['free_delivery_from_uah_sum'];
        if ($freeDeliveryFromSum !== null && $orderAmount >= $freeDeliveryFromSum){
            return 0;
        }

        return $deliverySettings['delivery_uah_price'];
    }
}
