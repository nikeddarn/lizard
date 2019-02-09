<?php
/**
 * Get products with updated price from vendor.
 * Create update product price job.
 */

namespace App\Support\Vendors\ProductManagers\Synchronization\UpdatedProduct;


use App\Models\SynchronizingProduct;
use App\Models\Vendor;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class SyncUpdatedProductManager
{
    /**
     * @var Collection
     */
    protected $vendorProductsToSync;
    /**
     * @var SynchronizingProduct
     */
    protected $synchronizingProduct;

    /**
     * SyncNewProductManager constructor.
     * @param SynchronizingProduct $synchronizingProduct
     */
    public function __construct(SynchronizingProduct $synchronizingProduct)
    {
        $this->synchronizingProduct = $synchronizingProduct;
    }

    /**
     * Create jobs of price modified products.
     *
     * @param Vendor $vendor
     * @throws Exception
     */
    public function synchronizeUpdatedProducts(Vendor $vendor)
    {
        // define last sync time
        $syncFromTime = $this->defineSyncFromTime($vendor);

        // retrieve sync data
        $newSyncAt = $this->getSyncProductsData($syncFromTime);

        // save new synchronized time
        if ($newSyncAt){
            $vendor->sync_prices_at = $newSyncAt;
            $vendor->save();
        }

        // create and dispatch jobs
        if (!empty($this->vendorProductsToSync)) {
            $this->dispatchProductsToUpdatingJobs();
        }
    }

    /**
     * Retrieve price modified products data from vendor.
     *
     * @param string|null $lastSynchronizedAt
     * @throws Exception
     */
    abstract protected function getSyncProductsData(string $lastSynchronizedAt = null);

    /**
     * Define synchronization from time.
     *
     * @param Vendor $vendor
     * @return string|null
     */
    private function defineSyncFromTime(Vendor $vendor)
    {
        if ($vendor->sync_prices_at){
            return $vendor->sync_prices_at;
        }else{
            $synchronizedProductsMinUpdatedTime = $vendor->vendorProducts()->min('updated_at');

            return $synchronizedProductsMinUpdatedTime ? $synchronizedProductsMinUpdatedTime : Carbon::now()->subDay();
        }
    }

    /**
     * Dispatch products to updating jobs.
     */
    private function dispatchProductsToUpdatingJobs()
    {
        foreach ($this->vendorProductsToSync as $vendorProduct) {
            // product's vendor categories ids
            $productVendorCategoriesIds = $vendorProduct->vendorCategories->pluck('id')->toArray();

            // product's local categories ids
            $productLocalCategoriesIds = $vendorProduct->product->categories->pluck('id')->toArray();

            DB::beginTransaction();
            // dispatch product to modifying queue
            $jobId = $this->dispatchJob($vendorProduct->vendor_product_id);

            // insert in synchronized products
            $this->insertSynchronizingProducts($jobId, $productVendorCategoriesIds, $productLocalCategoriesIds, $vendorProduct->vendor_product_id);
            DB::commit();
        }
    }

    /**
     * Create and dispatch jos
     *
     * @param int $vendorProductId
     * @return int
     */
    abstract protected function dispatchJob(int $vendorProductId): int;


}
