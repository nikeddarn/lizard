<?php
/**
 * Vendor broker.
 */

namespace App\Support\Vendors;


use App\Contracts\Vendor\VendorInterface;
use App\Contracts\Vendor\VendorProviderInterface;
use App\Support\Vendors\Providers\BrainVendorProvider;

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
}