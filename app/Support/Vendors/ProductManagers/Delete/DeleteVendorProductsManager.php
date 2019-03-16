<?php
/**
 * Delete product and vendor product if allowed.
 */

namespace App\Support\Vendors\ProductManagers\Delete;


use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\VendorLocalCategory;
use App\Models\VendorProduct;
use App\Support\ImageHandlers\ProductImageHandler;
use App\Support\Settings\SettingsRepository;

class DeleteVendorProductsManager
{
    /**
     * @var VendorProduct
     */
    private $vendorProduct;
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var VendorLocalCategory
     */
    private $vendorLocalCategory;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var CategoryProduct
     */
    private $categoryProduct;
    /**
     * @var ProductImageHandler
     */
    private $productImageHandler;

    /**
     * DeleteVendorProductManager constructor.
     * @param SettingsRepository $settingsRepository
     * @param VendorProduct $vendorProduct
     * @param Product $product
     * @param VendorLocalCategory $vendorLocalCategory
     * @param Category $category
     * @param CategoryProduct $categoryProduct
     * @param ProductImageHandler $productImageHandler
     */
    public function __construct(SettingsRepository $settingsRepository, VendorProduct $vendorProduct, Product $product, VendorLocalCategory $vendorLocalCategory, Category $category, CategoryProduct $categoryProduct, ProductImageHandler $productImageHandler)
    {
        $this->vendorProduct = $vendorProduct;
        $this->settingsRepository = $settingsRepository;
        $this->product = $product;
        $this->vendorLocalCategory = $vendorLocalCategory;
        $this->category = $category;
        $this->categoryProduct = $categoryProduct;
        $this->productImageHandler = $productImageHandler;
    }

    /**
     * Delete allowed products.
     *
     * @param array $vendorOwnProductsIds
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @return bool
     */
    public function unlinkVendorProducts(array $vendorOwnProductsIds, int $vendorCategoryId, int $localCategoryId): bool
    {
        // delete vendor products
        $deletingProductsIds = $this->deleteVendorProducts($vendorOwnProductsIds, $vendorCategoryId, $localCategoryId);

        // delete products
        $this->deleteProducts($deletingProductsIds);

        // unlink products from local category
        $this->unlinkLocalCategory($deletingProductsIds, $vendorCategoryId, $localCategoryId);

        return count($vendorOwnProductsIds) === count($deletingProductsIds);
    }

    /**
     * Delete vendor products.
     *
     * @param array $vendorOwnProductsIds
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @return array
     */
    private function deleteVendorProducts(array $vendorOwnProductsIds, int $vendorCategoryId, int $localCategoryId): array
    {
        $keepLinkOnDelete = $this->settingsRepository->getProperty('vendor.delete_product')['keep_link_in_stock_present_product_on_delete'];

        $deletingVendorProductsQuery = $this->vendorProduct->newQuery()
            ->whereIn('vendor_product_id', $vendorOwnProductsIds)
            ->whereHas('vendorCategoryProducts', function ($query) use ($vendorCategoryId) {
                $query->where('vendor_categories_id', $vendorCategoryId);
            })
            ->has('vendorCategoryProducts', '=', 1)
            ->whereHas('product', function ($query) use ($localCategoryId, $keepLinkOnDelete) {
                $query->whereHas('categoryProducts', function ($query) use ($localCategoryId) {
                    $query->where('categories_id', $localCategoryId);
                })
                    ->has('categoryProducts', '<=', 1);
                if ($keepLinkOnDelete) {
                    $query->doesntHave('stockStorageProducts');
                }
            });

        $deletingProductsIds = $deletingVendorProductsQuery->get()->pluck('products_id')->toArray();

        $deletingVendorProductsQuery->delete();

        return $deletingProductsIds;
    }

    /**
     * Delete vendor products.
     *
     * @param array $deletingProductsIds
     */
    private function deleteProducts(array $deletingProductsIds)
    {
        $archiveProductOnDelete = $this->settingsRepository->getProperty('shop.delete_product')['archive_product_on_delete'];

        $deletingProductsQuery = $this->product->newQuery()
            ->whereIn('id', $deletingProductsIds)
            ->doesntHave('stockStorageProducts')
            ->doesntHave('vendorProducts')
            ->has('categoryProducts', '<=', 1);

        if ($archiveProductOnDelete) {
            // archive products
            $deletingProductsQuery->update([
                'is_archive' => 1,
            ]);
        } else {
            // get deleting products ids
            $deletedProductsIds = $deletingProductsQuery->get()->pluck('id')->toArray();

            if ($deletedProductsIds) {
                // remove product images from storage
                $this->productImageHandler->deleteProductsImages($deletedProductsIds);
                //delete products
                $deletingProductsQuery->delete();
            }
        }
    }

    /**
     * Unlink allowed product from local category.
     *
     * @param array $deletingProductsIds
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     */
    private function unlinkLocalCategory(array $deletingProductsIds, int $vendorCategoryId, int $localCategoryId)
    {
        $this->categoryProduct->newQuery()
            ->whereIn('products_id', $deletingProductsIds)
            ->where('categories_id', $localCategoryId)
            ->whereHas('product', function ($query) use ($vendorCategoryId) {
                $query->doesntHave('stockStorageProducts')
                    ->whereDoesntHave('vendorProducts.vendorCategoryProducts', function ($query) use ($vendorCategoryId) {
                        $query->where('vendor_categories_id', '<>', $vendorCategoryId);
                    });
            })
            ->delete();
    }
}
