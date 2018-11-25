<?php
/**
 * Vendor broker.
 */

namespace App\Support\Vendors;


use App\Contracts\Vendor\VendorInterface;
use App\Contracts\Vendor\VendorAdapterInterface;
use App\Support\ProductPrices\ProductPrice;
use App\Support\Vendors\Adapters\BrainVendorAdapter;
use App\Support\Vendors\ProductManagers\BrainProductManager;
use App\Support\Vendors\Providers\BrainVendorProvider;
use App\Support\Vendors\Setup\BrainSetupManager;
use Illuminate\Container\Container;

class VendorBroker
{
    /**
     * Get vendor provider.
     *
     * @param string $vendorId
     * @return BrainVendorProvider
     */
    public function getVendorProvider(string $vendorId)
    {
        switch ($vendorId) {
            case VendorInterface::BRAIN:
                return new BrainVendorProvider();

            default:
                return abort(422);
        }
    }

    /**
     * Get vendor adapter.
     *
     * @param string $vendorId
     * @return BrainVendorAdapter
     */
    public function getVendorAdapter(string $vendorId)
    {
        switch ($vendorId) {
            case VendorInterface::BRAIN:
                return Container::getInstance()->make(BrainVendorAdapter::class);

            default:
                return abort(422);
        }
    }

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

    /**
     * Get vendor product manager.
     *
     * @param string $vendorId
     * @return BrainProductManager
     */
    public function getVendorProductManager(string $vendorId)
    {
        switch ($vendorId) {
            case VendorInterface::BRAIN:
                return Container::getInstance()->make(BrainProductManager::class);

            default:
                return abort(422);
        }
    }
}