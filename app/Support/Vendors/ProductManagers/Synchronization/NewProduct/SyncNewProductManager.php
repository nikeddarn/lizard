<?php
/**
 * Get new products from vendor.
 * Create insert new product price job.
 */

namespace App\Support\Vendors\ProductManagers\Synchronization\NewProduct;


use App\Models\SynchronizingProduct;
use App\Models\Vendor;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

abstract class SyncNewProductManager
{
    /**
     * @var array
     */
    protected $syncProductsData;
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
     * Create insert new product jobs
     *
     * @param Vendor $vendor
     * @throws Exception
     */
    public function synchronizeNewProducts(Vendor $vendor)
    {
        // define last sync time
        $syncFromTime = $this->defineSyncFromTime($vendor);

        // retrieve sync data
        $newSyncAt = $this->getSyncProductsData($syncFromTime);

        // save new synchronized time
        if ($newSyncAt) {
            $vendor->sync_new_products_at = $newSyncAt;
            $vendor->save();
        }

        // create and dispatch jobs
        if (!empty($syncProductsData)) {
            $this->dispatchProductsToUpdatingJobs();
        }
    }

    /**
     * Retrieve price modified products data from vendor.
     *
     * @param string|null $lastSynchronizedAt
     * @throws Exception
     */
    abstract protected function getSyncProductsData(string $lastSynchronizedAt);

    /**
     * Define synchronization from time.
     *
     * @param Vendor $vendor
     * @return string|null
     */
    private function defineSyncFromTime(Vendor $vendor)
    {
        if ($vendor->sync_new_products_at) {
            return $vendor->sync_new_products_at;
        } else {
            $synchronizedCategoriesMinUpdatedTime = $vendor->vendorLocalCategories()->min('updated_at');

            return $synchronizedCategoriesMinUpdatedTime ? $synchronizedCategoriesMinUpdatedTime : Carbon::now()->subDay();
        }
    }

    /**
     * Dispatch products to updating jobs.
     */
    private function dispatchProductsToUpdatingJobs()
    {
        foreach ($this->syncProductsData as $vendorProductId => $ProductData) {

            // product vendor categories
            $vendorCategories = $ProductData['vendor_categories'];

            // product local categories
            $localCategories = $ProductData['local_categories'];

            DB::beginTransaction();
            // dispatch product to modifying queue
            $jobId = $this->dispatchJob($vendorCategories, $localCategories, $vendorProductId);

            // insert in synchronized products
            $this->insertSynchronizingProducts($jobId, $vendorCategories, $localCategories, $vendorProductId);
            DB::commit();
        }
    }
}
