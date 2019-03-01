<?php
/**
 * Delete product and vendor product if allowed.
 */

namespace App\Support\Vendors\ProductManagers\Delete;


use App\Models\VendorProduct;
use App\Support\Settings\SettingsRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DeleteVendorProductManager
{
    /**
     * @var array
     */
    private $deleteProductSettings;

    /**
     * DeleteVendorProductManager constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->deleteProductSettings = $settingsRepository->getProperty('vendor.delete_product');
    }

    /**
     * Delete vendor products if allowed.
     *
     * @param Collection $vendorProducts
     * @param int $localCategoryId
     * @return bool
     * @throws \Exception
     */
    public function deleteVendorProducts(Collection $vendorProducts, int $localCategoryId):bool
    {
        $vendorProducts->keyBy('id');

        foreach ($vendorProducts as $vendorProduct){
            $vendorProductId = $vendorProduct->id;

            if ($this->deleteVendorProduct($vendorProduct, $localCategoryId)){
                $vendorProducts->forget($vendorProductId);
            }
        }

        return !$vendorProducts->count();
    }

    /**
     * Delete vendor product if allowed.
     *
     * @param VendorProduct|Model $vendorProduct
     * @param int $localCategoryId
     * @return bool
     * @throws \Exception
     */
    public function deleteVendorProduct(VendorProduct $vendorProduct, int $localCategoryId):bool
    {
        $product = $vendorProduct->product;

        // product presents in stock
        if ($product->stockStorages->count){

            if (!$this->deleteProductSettings['keep_link_in_stock_present_product_on_delete']){
                $vendorProduct->delete();

                return true;
            }

            return false;
        }

        if ($product->categories->count() === 1){
            if ($product->vendorProducts->count() === 1){
                // delete product
                $product->delete();
            }

            // delete vendor product
            $vendorProduct->delete();
        }

        // unlink from local category
        $product->categories()->detach($localCategoryId);

        return true;
    }
}
