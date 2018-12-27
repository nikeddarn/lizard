<?php
/**
 * Get products with updated price from vendor.
 * Create update product price job.
 */

namespace App\Support\Vendors\ProductManagers\Synchronization\UpdatedProduct;


use App\Contracts\Vendor\SyncTypeInterface;
use App\Models\SynchronizingProduct;
use App\Models\VendorProduct;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class SyncUpdatedProductManager
{
    /**
     * @var int
     */
    protected $vendorId;
    /**
     * @var Collection
     */
    protected $vendorProductsToSync;
    /**
     * @var string
     */
    protected $synchronizedAt = null;
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
     * @param string|null $lastSynchronizedAt
     * @return string|null
     */
    public function synchronizeUpdatedProducts(string $lastSynchronizedAt = null)
    {
        // define last sync time
        $syncFromTime = $lastSynchronizedAt ? $lastSynchronizedAt : $this->defineSyncFromTime();

        // nothing to sync
        if (!$syncFromTime) {
            return null;
        }

        // retrieve sync data
        $this->getSyncProductsData($syncFromTime);

        // create and dispatch jobs
        if (isset($this->vendorProductsToSync)) {
            $this->dispatchProductsToUpdatingJobs();
        }

        return $this->synchronizedAt;
    }

    /**
     * Retrieve price modified products data from vendor.
     *
     * @param string|null $lastSynchronizedAt
     */
    abstract protected function getSyncProductsData(string $lastSynchronizedAt = null);

    /**
     * Define synchronization from time.
     *
     * @return string|null
     */
    private function defineSyncFromTime()
    {
        return VendorProduct::query()->where('vendors_id', $this->vendorId)->min('updated_at');
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
            $jobId = $this->dispatchJob($this->vendorId, $vendorProduct->vendor_product_id);

            // insert in synchronized products
            $this->insertSynchronizingProducts($jobId, $this->vendorId, $productVendorCategoriesIds, $productLocalCategoriesIds, $vendorProduct->vendor_product_id);
            DB::commit();
        }
    }

    /**
     * Create and dispatch jos
     *
     * @param int $vendorId
     * @param int $vendorProductId
     * @return int
     */
    abstract protected function dispatchJob(int $vendorId, int $vendorProductId): int;

    /**
     * Insert product in synchronizing products
     *
     * @param int $jobId
     * @param int $vendorId
     * @param array $vendorCategoriesIds
     * @param array $localCategoriesIds
     * @param int $vendorProductId
     */
    private function insertSynchronizingProducts(int $jobId, int $vendorId, array $vendorCategoriesIds, array $localCategoriesIds, int $vendorProductId)
    {
        // insert for each vendor category
        foreach ($vendorCategoriesIds as $vendorCategory) {
            // insert for each local category
            foreach ($localCategoriesIds as $localCategory) {
                // create model
                $this->synchronizingProduct->newQuery()->create([
                    'jobs_id' => $jobId,
                    'vendor_product_id' => $vendorProductId,
                    'vendors_id' => $vendorId,
                    'sync_type' => SyncTypeInterface::UPDATE_PRODUCT,
                    'vendor_categories_id' => $vendorCategory,
                    'categories_id' => $localCategory,
                ]);
            }
        }
    }
}
