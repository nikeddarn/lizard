<?php
/**
 * Retrieve Brain sync products prices data.
 */

namespace App\Support\Vendors\ProductManagers\Synchronization\UpdatedProduct;


use App\Contracts\Vendor\SyncTypeInterface;
use App\Contracts\Vendor\VendorInterface;
use App\Jobs\Vendors\UpdateVendorProduct;
use App\Models\SynchronizingProduct;
use App\Models\VendorProduct;
use Illuminate\Bus\Dispatcher;
use App\Support\Vendors\Providers\BrainSyncUpdatedProductProvider;
use Exception;
use Illuminate\Support\Collection;

class BrainSyncUpdatedProductManager extends SyncUpdatedProductManager
{
    /**
     * @var BrainSyncUpdatedProductProvider
     */
    private $provider;
    /**
     * @var VendorProduct
     */
    private $vendorProduct;

    /**
     * BrainSyncProductPriceManager constructor.
     * @param BrainSyncUpdatedProductProvider $provider
     * @param VendorProduct $vendorProduct
     * @param SynchronizingProduct $synchronizingProduct
     */
    public function __construct(BrainSyncUpdatedProductProvider $provider, VendorProduct $vendorProduct, SynchronizingProduct $synchronizingProduct)
    {
        parent::__construct($synchronizingProduct);

        $this->provider = $provider;
        $this->vendorProduct = $vendorProduct;
    }

    /**
     * Retrieve price modified products data from vendor.
     *
     * @param string|null $lastSynchronizedAt
     * @return string
     * @throws Exception
     */
    protected function getSyncProductsData(string $lastSynchronizedAt = null)
    {
        $vendorModifiedProductsIds = $this->provider->getUpdatedProductsIds($lastSynchronizedAt);

        if ($vendorModifiedProductsIds) {
            $this->vendorProductsToSync = $this->getVendorProductsToSync($vendorModifiedProductsIds);
        }

        return $this->provider->getVendorSynchronizedAt();
    }

    /**
     * Create and dispatch jos
     *
     * @param int $vendorProductId
     * @return int
     */
    protected function dispatchJob(int $vendorProductId): int
    {
        // create job
        $job = new UpdateVendorProduct(VendorInterface::BRAIN, $vendorProductId);

        $job->onConnection('database')
            ->onQueue(SyncTypeInterface::UPDATE_PRODUCT);

        // dispatch job
        $jobId = app(Dispatcher::class)->dispatch($job);

        return $jobId;
    }

    /**
     * Get vendor products ids that presents in DB and needing to price sync.
     *
     * @param array $vendorModifiedProductsIds
     * @return Collection
     */
    private function getVendorProductsToSync(array $vendorModifiedProductsIds): Collection
    {
        // queued products ids
        $queuedVendorProductsIds = $this->getQueuedVendorProductsIds($vendorModifiedProductsIds);

        // synchronized price modified not queued vendor products
        $synchronizedModifiedNotQueuedProducts = $this->getSynchronizedVendorProductsIds($vendorModifiedProductsIds, $queuedVendorProductsIds);

        return $synchronizedModifiedNotQueuedProducts;
    }

    /**
     * @param array $vendorModifiedProductsIds
     * @param array $queuedVendorProductsIds
     * @return \Illuminate\Support\Collection
     */
    private function getSynchronizedVendorProductsIds(array $vendorModifiedProductsIds, array $queuedVendorProductsIds): Collection
    {
        return $this->vendorProduct->newQuery()
            ->where('vendors_id', VendorInterface::BRAIN)
            ->whereIn('vendor_product_id', $vendorModifiedProductsIds)
            ->whereNotIn('vendor_product_id', $queuedVendorProductsIds)
            ->with('vendorCategories', 'product.categories')
            ->get();
    }


    /**
     * Get already queued vendor products ids.
     *
     * @param array $vendorNewProductsIds
     * @return array
     */
    private function getQueuedVendorProductsIds(array $vendorNewProductsIds): array
    {
        return $this->synchronizingProduct->newQuery()
            ->where([
                ['vendors_id', '=', VendorInterface::BRAIN],
                ['sync_type', '=', SyncTypeInterface::UPDATE_PRODUCT],
            ])
            ->whereIn('vendor_product_id', $vendorNewProductsIds)
            ->select('vendor_product_id')
            ->distinct()
            ->get()
            ->pluck('vendor_product_id')
            ->toArray();
    }

    /**
     * Insert product in synchronizing products
     *
     * @param int $jobId
     * @param array $vendorCategoriesIds
     * @param array $localCategoriesIds
     * @param int $vendorProductId
     */
    protected function insertSynchronizingProducts(int $jobId, array $vendorCategoriesIds, array $localCategoriesIds, int $vendorProductId)
    {
        // insert for each vendor category
        foreach ($vendorCategoriesIds as $vendorCategory) {
            // insert for each local category
            foreach ($localCategoriesIds as $localCategory) {
                // create model
                $this->synchronizingProduct->newQuery()->create([
                    'jobs_id' => $jobId,
                    'vendor_product_id' => $vendorProductId,
                    'vendors_id' => VendorInterface::BRAIN,
                    'sync_type' => SyncTypeInterface::UPDATE_PRODUCT,
                    'vendor_categories_id' => $vendorCategory,
                    'categories_id' => $localCategory,
                ]);
            }
        }
    }
}
