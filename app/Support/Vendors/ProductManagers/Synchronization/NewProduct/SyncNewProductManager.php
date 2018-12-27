<?php
/**
 * Get new products from vendor.
 * Create insert new product price job.
 */

namespace App\Support\Vendors\ProductManagers\Synchronization\NewProduct;


use App\Contracts\Vendor\SyncTypeInterface;
use App\Jobs\Vendors\InsertVendorProduct;
use App\Models\SynchronizingProduct;
use App\Models\VendorLocalCategory;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\DB;

abstract class SyncNewProductManager
{
    /**
     * @var int
     */
    protected $vendorId;
    /**
     * @var array
     */
    protected $syncProductsData = [];
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
     * Create insert new product jobs
     *
     * @param string|null $lastSynchronizedAt
     * @return string|null
     */
    public function synchronizeNewProducts(string $lastSynchronizedAt = null)
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
        $this->dispatchProductsToUpdatingJobs();

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
        return VendorLocalCategory::query()
            ->whereHas('vendorCategory', function ($query) {
                $query->where('vendors_id', $this->vendorId);
            })
            ->min('updated_at');
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
            $jobId = $this->dispatchJob($this->vendorId, $vendorCategories, $localCategories, $vendorProductId);

            // insert in synchronized products
            $this->insertSynchronizingProducts($jobId, $this->vendorId, $vendorCategories, $localCategories, $vendorProductId);
            DB::commit();
        }
    }

    /**
     * Create and dispatch jos
     *
     * @param int $vendorId
     * @param array $vendorCategories
     * @param array $localCategories
     * @param int $vendorProductId
     * @return int
     */
    private function dispatchJob(int $vendorId, array $vendorCategories, array $localCategories, int $vendorProductId): int
    {
        // create job
        $job = new InsertVendorProduct($vendorId, $vendorCategories, $localCategories, $vendorProductId);

        $job->onConnection('database')
            ->onQueue(SyncTypeInterface::INSERT_PRODUCT);

        // dispatch job
        $jobId = app(Dispatcher::class)->dispatch($job);

        return $jobId;
    }

    /**
     * Insert product in synchronizing products
     *
     * @param int $jobId
     * @param int $vendorId
     * @param array $vendorCategories
     * @param array $localCategories
     * @param int $vendorProductId
     */
    private function insertSynchronizingProducts(int $jobId, int $vendorId, array $vendorCategories, array $localCategories, int $vendorProductId)
    {
        // insert for each vendor category
        foreach ($vendorCategories as $vendorCategory){
            // insert for each local category
            foreach ($localCategories as $localCategory){
                // create model
                $this->synchronizingProduct->newQuery()->create([
                    'jobs_id' => $jobId,
                    'vendor_product_id' => $vendorProductId,
                    'vendors_id' => $vendorId,
                    'sync_type' => SyncTypeInterface::INSERT_PRODUCT,
                    'vendor_categories_id' => $vendorCategory,
                    'categories_id' => $localCategory,
                ]);
            }
        }
    }
}
