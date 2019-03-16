<?php

namespace App\Listeners\Shop;

use App\Support\Archive\ArchiveProductManager;
use App\Support\ImageHandlers\ProductImageHandler;
use App\Support\Settings\SettingsRepository;

class ArchiveOrDeleteProduct
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;
    /**
     * @var ArchiveProductManager
     */
    private $archiveProductManager;
    /**
     * @var ProductImageHandler
     */
    private $productImageHandler;

    /**
     * ArchiveOrDeleteProduct constructor.
     * @param SettingsRepository $settingsRepository
     * @param ArchiveProductManager $archiveProductManager
     * @param ProductImageHandler $productImageHandler
     */
    public function __construct(SettingsRepository $settingsRepository, ArchiveProductManager $archiveProductManager, ProductImageHandler $productImageHandler)
    {
        $this->settingsRepository = $settingsRepository;
        $this->archiveProductManager = $archiveProductManager;
        $this->productImageHandler = $productImageHandler;
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return bool
     */
    public function handle($event): bool
    {
        // get product
        $product = $event->product;

        // get settings
        $archiveProductOnDelete = $this->settingsRepository->getProperty('shop.delete_product')['archive_product_on_delete'];

        // archive by settings or if product on any storage
        if ($archiveProductOnDelete || $product->storageProducts()->count()) {
            // archive product existing in any storage or by settings
            $this->archiveProductManager->archiveProduct($product);

            // prevent to delete product
            return false;
        } else {
            // remove product images from storage
            $this->productImageHandler->deleteProductImage($product->id);

            // continue deleting product
            return true;
        }
    }
}
