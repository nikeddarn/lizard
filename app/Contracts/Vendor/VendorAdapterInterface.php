<?php
/**
 * Vendor adapter interface.
 */

namespace App\Contracts\Vendor;


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
}
