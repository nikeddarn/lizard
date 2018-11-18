<?php
/**
 * Insert and remove vendor products.
 */

namespace App\Support\Vendor;


use App\Contracts\Vendor\VendorProviderInterface;

class VendorProductManager
{
    /**
     * Insert vendor products data in db.
     *
     * @param VendorProviderInterface $vendorProvider
     * @param array $vendorProductsIds
     */
    public function insertVendorProducts(VendorProviderInterface $vendorProvider, array $vendorProductsIds)
    {
        $productsDataRu = $vendorProvider->getProductsData($vendorProductsIds, 'ru');

        var_dump(implode(' ',$productsDataRu->pluck('country')->toArray()));exit;
    }

    /**
     * Delete vendor products data in db.
     *
     * @param VendorProviderInterface $vendorProvider
     * @param array $vendorProductsIds
     */
    public function deleteVendorProducts(VendorProviderInterface $vendorProvider, array $vendorProductsIds)
    {

    }
}