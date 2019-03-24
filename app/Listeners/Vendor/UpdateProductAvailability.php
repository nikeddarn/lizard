<?php

namespace App\Listeners\Vendor;

use App\Contracts\Shop\ProductBadgesInterface;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductBadges\ProductBadges;
use App\Support\Settings\SettingsRepository;

class UpdateProductAvailability
{
    /**
     * @var ProductAvailability
     */
    private $productAvailability;
    /**
     * @var ProductBadges
     */
    private $productBadges;
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * Create the event listener.
     *
     * @param ProductAvailability $productAvailability
     * @param ProductBadges $productBadges
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(ProductAvailability $productAvailability, ProductBadges $productBadges, SettingsRepository $settingsRepository)
    {
        $this->productAvailability = $productAvailability;
        $this->productBadges = $productBadges;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        // get product
        $product = $event->product;

        // add or remove 'ending badge'
        if ($this->productAvailability->isProductEnding($product)) {
            $this->productBadges->insertProductBadge($product, ProductBadgesInterface::ENDING);
        } else {
            $this->productBadges->deleteProductBadge($product, ProductBadgesInterface::ENDING);
        }

        // delete or archive product if all vendor products are archived
        if (!$this->productAvailability->isProductAvailableOrExpecting($product)) {

            $deleteProductOnVendorArchive = $this->settingsRepository->getProperty('vendor.delete_product')['delete_product_on_archive_vendor_product'];

            if ($deleteProductOnVendorArchive && $product->vendorProducts->min('is_archive')) {
                // delete or archive product
                $product->delete();
            }
        }
    }
}
