<?php
/**
 * Delete product and vendor product if allowed.
 */

namespace App\Support\Vendors\ProductManagers\Delete;


use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\VendorProduct;
use App\Support\Settings\SettingsRepository;
use Illuminate\Support\Facades\DB;

class UnlinkVendorProductManager
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
     * @var CategoryProduct
     */
    private $categoryProduct;

    /**
     * DeleteVendorProductManager constructor.
     * @param SettingsRepository $settingsRepository
     * @param VendorProduct $vendorProduct
     * @param Product $product
     * @param CategoryProduct $categoryProduct
     */
    public function __construct(SettingsRepository $settingsRepository, VendorProduct $vendorProduct, Product $product, CategoryProduct $categoryProduct)
    {
        $this->vendorProduct = $vendorProduct;
        $this->settingsRepository = $settingsRepository;
        $this->product = $product;
        $this->categoryProduct = $categoryProduct;
    }

    /**
     * Delete single vendor product
     *
     * @param int $vendorProductId
     * @param int $productId
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @return bool|\Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function unlinkSingleVendorProduct(int $vendorProductId, int $productId, int $vendorCategoryId, int $localCategoryId): bool
    {
        DB::beginTransaction();

        // delete vendor product
        if ($this->deleteVendorProduct($vendorProductId)) {
            // delete product
            if ($this->deleteProduct($productId)) {
                $isDeleted = true;
            } else {
                // unlink product from local category
                $isDeleted = $this->unlinkLocalCategory($productId, $vendorCategoryId, $localCategoryId);
            }
        } else {
            // unlink product from local category
            $isDeleted = $this->unlinkLocalCategory($productId, $vendorCategoryId, $localCategoryId);
        }

        DB::commit();

        return $isDeleted;
    }

    /**
     * Delete allowed vendor product.
     *
     * @param int $vendorProductId
     * @return bool
     * @throws \Exception
     */
    private function deleteVendorProduct(int $vendorProductId): bool
    {
        $keepLinkOnDelete = $this->settingsRepository->getProperty('vendor.delete_product')['keep_link_in_stock_present_product_on_delete'];

        return $this->vendorProduct->newQuery()
            ->where('id', $vendorProductId)
            ->has('vendorCategoryProducts', '=', 1)
            ->whereHas('product', function ($query) use ($keepLinkOnDelete) {
                $query->has('categoryProducts', '<=', 1);
                if ($keepLinkOnDelete) {
                    $query->doesntHave('stockStorageProducts');
                }
            })
            ->delete();
    }

    /**
     * Delete allowed product.
     *
     * @param int $productId
     * @return bool
     */
    private function deleteProduct(int $productId)
    {
        // get allowed product
        $product = $this->product->newQuery()
            ->where('id', $productId)
            ->doesntHave('stockStorageProducts')
            ->doesntHave('vendorProducts')
            ->has('categoryProducts', '<=', 1);

        if ($product) {
            $product->delete();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Unlink allowed product from local category.
     *
     * @param int $productId
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function unlinkLocalCategory(int $productId, int $vendorCategoryId, int $localCategoryId)
    {
        return $this->categoryProduct->newQuery()
            ->where('products_id', $productId)
            ->where('categories_id', $localCategoryId)
            ->whereHas('product', function ($query) use ($vendorCategoryId) {
                $query->doesntHave('stockStorageProducts')
                ->whereDoesntHave('vendorProducts.vendorCategoryProducts', function ($query) use ($vendorCategoryId){
                    $query->where('vendor_categories_id', '<>', $vendorCategoryId);
                });
            })
            ->delete();
    }
}
