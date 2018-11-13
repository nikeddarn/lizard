<?php
/**
 * Insert and remove vendor products.
 */

namespace App\Support\Vendor;


use App\Support\Vendors\VendorBroker;

class VendorProductManager
{
    /**
     * @var VendorBroker
     */
    private $vendorBroker;

    /**
     * VendorProductManager constructor.
     * @param VendorBroker $vendorBroker
     */
    public function __construct(VendorBroker $vendorBroker)
    {
        $this->vendorBroker = $vendorBroker;
    }

    /**
     * Insert vendor products data in db.
     *
     * @param int $vendorId
     * @param array $vendorProductsIds
     * @throws \Exception
     */
    public function insertVendorProducts(int $vendorId, array $vendorProductsIds)
    {
        $productsDataRu = $this->vendorBroker->getVendorProvider($vendorId)->getProductsData($vendorProductsIds, 'ru');

        var_dump(implode(' ',$productsDataRu->pluck('country')->toArray()));exit;
    }

    /**
     * Delete vendor products data in db.
     *
     * @param int $vendorId
     * @param array $vendorProductsIds
     * @throws \Exception
     */
    public function deleteVendorProducts(int $vendorId, array $vendorProductsIds)
    {

    }
}