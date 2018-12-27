<?php
/**
 * Vendor broker.
 */

namespace App\Support\Vendors;


use App\Contracts\Vendor\VendorInterface;
use App\Support\Vendors\ProductManagers\Catalog\BrainVendorCatalogManager;
use App\Support\Vendors\ProductManagers\Insert\BrainInsertVendorProductManager;
use App\Support\Vendors\ProductManagers\Synchronization\NewProduct\BrainSyncNewProductManager;
use App\Support\Vendors\ProductManagers\Synchronization\UpdatedProduct\BrainSyncUpdatedProductManager;
use App\Support\Vendors\ProductManagers\Update\BrainUpdateVendorProductManager;
use App\Support\Vendors\Setup\BrainSetupManager;
use Illuminate\Container\Container;

class VendorBroker
{
    // ----------------------------------------- Setup -----------------------------------------------------------------

    /**
     * Get vendor setup manager.
     *
     * @param string $vendorId
     * @return BrainSetupManager
     */
    public function getVendorSetupManager(string $vendorId)
    {
        switch ($vendorId) {
            case VendorInterface::BRAIN:
                return Container::getInstance()->make(BrainSetupManager::class);

            default:
                return abort(422);
        }
    }

    // ----------------------------------------- Catalog ---------------------------------------------------------------

    /**
     * Get vendor catalog manager.
     *
     * @param string $vendorId
     * @return BrainVendorCatalogManager
     */
    public function getVendorCatalogManager(string $vendorId)
    {
        switch ($vendorId) {
            case VendorInterface::BRAIN:
                return Container::getInstance()->make(BrainVendorCatalogManager::class);

            default:
                return abort(422);
        }
    }


    // ----------------------------- Check products for synchronization in jobs-----------------------------------------

    /**
     * Get sync product price manager.
     *
     * @param string $vendorId
     * @return BrainSyncUpdatedProductManager
     */
    public function getSyncUpdatedProductManager(string $vendorId)
    {
        switch ($vendorId) {
            case VendorInterface::BRAIN:
                return Container::getInstance()->make(BrainSyncUpdatedProductManager::class);

            default:
                return abort(422);
        }
    }

    /**
     * Get sync new products manager.
     *
     * @param string $vendorId
     * @return BrainSyncNewProductManager
     */
    public function getSyncNewProductManager(string $vendorId)
    {
        switch ($vendorId) {
            case VendorInterface::BRAIN:
                return Container::getInstance()->make(BrainSyncNewProductManager::class);

            default:
                return abort(422);
        }
    }

    // -------------------------------------------- Job managers -------------------------------------------------------

    /**
     * Get insert new vendor product manager.
     *
     * @param string $vendorId
     * @return BrainInsertVendorProductManager
     */
    public function getInsertProductJobManager(string $vendorId)
    {
        switch ($vendorId) {
            case VendorInterface::BRAIN:
                return Container::getInstance()->make(BrainInsertVendorProductManager::class);

            default:
                return abort(422);
        }
    }

    /**
     * Get insert new vendor product manager.
     *
     * @param string $vendorId
     * @return BrainUpdateVendorProductManager
     */
    public function getUpdateProductJobManager(string $vendorId)
    {
        switch ($vendorId) {
            case VendorInterface::BRAIN:
                return Container::getInstance()->make(BrainUpdateVendorProductManager::class);

            default:
                return abort(422);
        }
    }
}
