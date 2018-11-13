<?php
/**
 * Vendor provider.
 */

namespace App\Contracts\Vendor;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface VendorProviderInterface
{
    /**
     * Get vendor categories tree.
     *
     * @return Collection
     */
    public function getCategories(): Collection;

    /**
     * Get vendor category by id.
     *
     * @param int $categoryId
     * @param string|null $locale
     * @return object
     */
    public function getCategory(int $categoryId, string $locale = null): object;

    /**
     * Get products of category.
     *
     * @param int $categoryId
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getCategoryProducts(int $categoryId, int $page): LengthAwarePaginator;

    /**
     * Get products data.
     *
     * @param array $vendorProductsIds
     * @param string $locale
     * @return Collection
     */
    public function getProductsData(array $vendorProductsIds, string $locale): Collection;
}