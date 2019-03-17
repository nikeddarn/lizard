<?php
/**
 * Vendor catalog manager.
 */

namespace App\Support\Vendors\ProductManagers\Catalog;


use App\Support\Vendors\Providers\BrainCatalogProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use stdClass;

abstract class VendorCatalogManager
{
    /**
     * @var BrainCatalogProvider
     */
    protected $provider;

    /**
     * Get tree of vendor categories.
     *
     * @return Collection
     * @throws \Exception
     */
    public function getVendorCategoriesTree(): Collection
    {
        $vendorCategories = $this->prepareCategoriesData();

        return $this->buildCategoriesTree($vendorCategories);
    }


    /**
     * Get vendor category parameters for view.
     *
     * @param int $vendorCategoryId
     * @return stdClass
     */
    public function getVendorCategoryData(int $vendorCategoryId): stdClass
    {
        return $this->prepareCategoryData($vendorCategoryId);
    }

    /**
     * Get vendor category data for insert.
     *
     * @param int $vendorCategoryId
     * @return array
     * @throws \Exception
     */
    public function getVendorCategoryModelData(int $vendorCategoryId): array
    {
        return $this->prepareVendorCategoryModelData($vendorCategoryId);
    }

    /**
     * Get Category's products of given page
     *
     * @param int $vendorCategoryId
     * @param int $page
     * @return LengthAwarePaginator
     * @throws \Exception
     */
    public function getCategoryPageProducts(int $vendorCategoryId, int $page): LengthAwarePaginator
    {
        return $this->createCategoryProductsPaginator($vendorCategoryId, $page);
    }

    /**
     * Get Category's products of given page
     *
     * @param int $vendorCategoryId
     * @return array
     * @throws \Exception
     */
    public function getAllProductsOfCategory(int $vendorCategoryId): array
    {
        return $this->getCategoryProductsIds($vendorCategoryId);
    }

    /**
     * prepare category parameters for view.
     *
     * @param $vendorCategoryId
     * @return stdClass
     */
    abstract protected function prepareCategoryData($vendorCategoryId): stdClass;

    /**
     * Get vendor categories.
     *
     * @return array
     */
    abstract protected function prepareCategoriesData(): array;

    /**
     * Retrieve and prepare vendor category data for insert.
     *
     * @param int $vendorCategoryId
     * @return array
     * @throws \Exception
     */
    abstract protected function prepareVendorCategoryModelData(int $vendorCategoryId): array;

    /**
     * Create paginator of category products.
     *
     * @param int $vendorCategoryId
     * @param int $page
     * @return LengthAwarePaginator
     */
    abstract protected function createCategoryProductsPaginator(int $vendorCategoryId, int $page): LengthAwarePaginator;

    /**
     * Get all products ids of given vendor category.
     *
     * @param int $vendorCategoryId
     * @return array
     */
    abstract protected function getCategoryProductsIds(int $vendorCategoryId):array;

    /**
     * Build tree from categories.
     *
     * @param array $elements
     * @param int $parentId
     * @return Collection
     */
    private function buildCategoriesTree(array &$elements, $parentId = 1): Collection
    {
        $branch = collect();

        foreach ($elements as $element) {
            if ($element->parentId == $parentId) {
                $children = $this->buildCategoriesTree($elements, $element->id);
                if ($children) {
                    $element->children = $children;
                }

                $branch->push($element);
            }
        }

        return $branch;
    }

    /**
     * Format product price.
     *
     * @param float $price
     * @return string
     */
    protected function formatPrice(float $price = null):string
    {
        return $price ? number_format($price, 2) : '';
    }

    /**
     * Format profit percents.
     *
     * @param float $percents
     * @return string
     */
    protected function formatProfitPercents(float $percents = null):string
    {
        return $percents ? number_format($percents, 1) : '';
    }
}
