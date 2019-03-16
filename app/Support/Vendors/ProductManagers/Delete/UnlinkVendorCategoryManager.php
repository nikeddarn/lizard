<?php
/**
 * Delete product and vendor product if allowed.
 */

namespace App\Support\Vendors\ProductManagers\Delete;


use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\VendorCategory;
use App\Models\VendorLocalCategory;
use App\Models\VendorProduct;
use App\Support\ImageHandlers\ProductImageHandler;
use App\Support\Settings\SettingsRepository;
use Illuminate\Support\Facades\DB;

class UnlinkVendorCategoryManager
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
     * @var VendorCategory
     */
    private $vendorCategory;
    /**
     * @var VendorLocalCategory
     */
    private $vendorLocalCategory;

    /**
     * DeleteVendorProductManager constructor.
     * @param SettingsRepository $settingsRepository
     * @param VendorProduct $vendorProduct
     * @param Product $product
     * @param Category $category
     * @param CategoryProduct $categoryProduct
     * @param ProductImageHandler $productImageHandler
     * @param VendorCategory $vendorCategory
     * @param VendorLocalCategory $vendorLocalCategory
     */
    public function __construct(SettingsRepository $settingsRepository, VendorProduct $vendorProduct, Product $product, Category $category, CategoryProduct $categoryProduct, ProductImageHandler $productImageHandler, VendorCategory $vendorCategory, VendorLocalCategory $vendorLocalCategory)
    {
        $this->vendorProduct = $vendorProduct;
        $this->settingsRepository = $settingsRepository;
        $this->product = $product;
        $this->category = $category;
        $this->categoryProduct = $categoryProduct;
        $this->productImageHandler = $productImageHandler;
        $this->vendorCategory = $vendorCategory;
        $this->vendorLocalCategory = $vendorLocalCategory;
    }

    public function unlinkVendorCategory(int $vendorCategoryId)
    {
        DB::beginTransaction();

        //get settings
        $deleteProductSettings = $this->settingsRepository->getProperty('vendor.delete_product');

        // delete vendor products
        $deletingProductsIds = $this->deleteVendorProducts($vendorCategoryId);

        // delete products
        $this->deleteProducts($deletingProductsIds);

        // unlink products from local category
        $deletingLocalCategoriesIds = $this->unlinkLocalCategory($deletingProductsIds, $vendorCategoryId);

        // unlink local categories
        $this->unlinkVendorLocalCategory($vendorCategoryId);

        // delete empty local category
        if ($deleteProductSettings['delete_empty_local_category_on_delete_vendor_category']) {
            $this->deleteEmptyLocalCategory($deletingLocalCategoriesIds);
        }

        // unlink vendor local category
        $isUnlinked = $this->deleteVendorCategory($vendorCategoryId);

        DB::commit();

        return $isUnlinked;

    }

    /**
     * Delete vendor products.
     *
     * @param int $vendorCategoryId
     * @return array
     */
    private function deleteVendorProducts(int $vendorCategoryId): array
    {
        $keepLinkOnDelete = $this->settingsRepository->getProperty('vendor.delete_product')['keep_link_in_stock_present_product_on_delete'];

        $deletingVendorProductsQuery = $this->vendorProduct->newQuery()
            ->whereHas('vendorCategoryProducts', function ($query) use ($vendorCategoryId) {
                $query->where('vendor_categories_id', $vendorCategoryId);
            })
            ->has('vendorCategoryProducts', '=', 1);

        if ($keepLinkOnDelete) {
            $deletingVendorProductsQuery->doesntHave('product.stockStorageProducts');
        }

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
            ->doesntHave('vendorProducts');

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
     * @return array
     */
    private function unlinkLocalCategory(array $deletingProductsIds, int $vendorCategoryId):array
    {
        $query =  $this->categoryProduct->newQuery()
            ->whereIn('products_id', $deletingProductsIds)
            ->whereHas('product', function ($query) use ($vendorCategoryId) {
                $query->doesntHave('stockStorageProducts')
                    ->whereDoesntHave('vendorProducts.vendorCategoryProducts', function ($query) use ($vendorCategoryId) {
                        $query->where('vendor_categories_id', '<>', $vendorCategoryId);
                    });
            });

        // get local categories ids
        $localCategoriesIds = $query->get()->pluck('categories_id')->toArray();

        // delete local categories
        $query->delete();

        return $localCategoriesIds;
    }

    /**
     * Delete empty local category
     *
     * @param array $deletingLocalCategoriesIds
     */
    private function deleteEmptyLocalCategory(array $deletingLocalCategoriesIds)
    {
        $this->category->newQuery()
            ->whereIn('id', $deletingLocalCategoriesIds)
            ->whereIsLeaf()
            ->doesntHave('products')
            ->delete();
    }

    /**
     * Unlink local category from vendor.
     *
     * @param int $vendorCategoryId
     * @return bool
     */
    private function unlinkVendorLocalCategory(int $vendorCategoryId)
    {
        return $this->vendorLocalCategory->newQuery()
            ->where('vendor_categories_id', $vendorCategoryId)
            ->whereDoesntHave('category.products', function ($query) use ($vendorCategoryId) {
                $query->whereHas('vendorProducts.vendorCategoryProducts', function ($query) use ($vendorCategoryId) {
                    $query->where('vendor_categories_id', $vendorCategoryId);
                });
            })
            ->delete();
    }

    /**
     * Unlink local category from vendor.
     *
     * @param int $vendorCategoryId
     * @return bool
     */
    private function deleteVendorCategory(int $vendorCategoryId)
    {
        return $this->vendorCategory->newQuery()
            ->where('id', $vendorCategoryId)
            ->whereDoesntHave('categories.products', function ($query) use ($vendorCategoryId) {
                $query->whereHas('vendorProducts.vendorCategoryProducts', function ($query) use ($vendorCategoryId) {
                    $query->where('vendor_categories_id', $vendorCategoryId);
                });
            })
            ->delete();
    }
}
