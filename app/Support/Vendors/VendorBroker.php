<?php
/**
 * Vendor broker.
 */

namespace App\Support\Vendors;


use App\Contracts\Vendor\VendorInterface;
use App\Contracts\Vendor\VendorProviderInterface;
use App\Support\ProductPrices\ProductPrice;
use App\Support\Vendors\Adapters\BrainVendorAdapter;
use App\Support\Vendors\ProductManagers\BrainProductManager;
use App\Support\Vendors\Providers\BrainVendorProvider;
use App\Support\Vendors\Setup\BrainSetupManager;

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
                return new BrainVendorAdapter(new BrainVendorProvider(), new ProductPrice());

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
                return new BrainSetupManager(new BrainVendorProvider());

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
                return new BrainProductManager(new BrainVendorAdapter(new BrainVendorProvider(), new ProductPrice()));

            default:
                return abort(422);
        }
    }
}