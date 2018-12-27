<?php
/**
 * Vendor product adapter interface.
 */

namespace App\Contracts\Vendor;


use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface VendorProductAdapterInterface
{
    /**
     * @param int $vendorProductId
     * @return array
     * @throws Exception
     */
    public function getProductData(int $vendorProductId);

    /**
     * @param int $vendorProductId
     * @return array
     * @throws Exception
     */
    public function getProductAttributesData(int $vendorProductId): array;

    /**
     * Get brand data by vendor's brand id.
     *
     * @param int $vendorBrandId
     * @return array
     * @throws Exception
     */
    public function getBrandDataByVendorBrandId(int $vendorBrandId):array;

    /**
     * Get brand data by vendor's brand id.
     *
     * @param int $vendorStockId
     * @return array
     * @throws Exception
     */
    public function getStockDataByVendorStockId(int $vendorStockId):array;
}
