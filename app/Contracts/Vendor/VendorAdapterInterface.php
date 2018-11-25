<?php
/**
 * Vendor provider.
 */

namespace App\Contracts\Vendor;


use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface VendorAdapterInterface
{
    /**
     * Get vendor id.
     *
     * @return int
     */
    public function getVendorId();


    /**
     * Get vendor categories tree.
     *
     * @return Collection
     * @throws \Exception
     */
    public function getVendorCategoriesTree(): Collection;

    /**
     * @param int $categoryId
     * @return array
     * @throws \Exception
     */
    public function getVendorCategoryData(int $categoryId): array;

    /**
     * Get page of category products.
     *
     * @param int $categoryId
     * @param int $page
     * @return LengthAwarePaginator
     * @throws \Exception
     */
    public function getCategoryProducts(int $categoryId, int $page): LengthAwarePaginator;

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